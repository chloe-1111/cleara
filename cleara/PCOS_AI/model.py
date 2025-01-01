import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

import tensorflow as tf
from tensorflow import feature_column
from tensorflow.keras import layers

from sklearn.model_selection import train_test_split
from sklearn.metrics import classification_report

import os
os.chdir("/Users/azimospanov/Desktop/aghanim") # change to your directory

os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3' # suppress warnings
tf.get_logger().setLevel('ERROR')
tf.autograph.set_verbosity(3)

tf.random.set_seed(42) # set random seed

#%% Parameters
batch_size = 32
epochs = 50
lr = 0.001
threshold = 0.6

#%% Read and Preprocess CSV file
df = pd.read_csv("data.csv", sep=";")
df = df.drop(["profile_id"], axis=1)
df = df.drop(["targeted_symptom"], axis=1)
df = df.rename(columns = {"output data": "target"})

train, test = train_test_split(df, test_size=0.2)

categories = ["spinach_1", 
          "spinach_2",
          "spinach_3",
          "cheakpeas_1",
          "cheakpeas_2",
          "cheakpeas_3",
          "salmon_1",
          "salmon_2",
          "salmon_3",
          "almonds_1",
          "almonds_2",
          "almonds_3", 
          "grapefruit_1",
          "grapefruit_2",
          "grapefruit_3"]

#%% Process dataframe
def process_target(df):
    df['target'] = df["target"].str.replace(' ', '')
    target = df.pop('target').to_numpy().tolist()
    for i, item in enumerate(target):
        one_hot = [0 for _ in range(15)]
        for label in item.split(','):
            one_hot[categories.index(label)] = 1
        target[i] = one_hot
    return target

#%% Transform dataframe to tensor
def df_to_dataset(dataframe, shuffle=True, batch_size=32):
    dataframe = dataframe.copy()
    targets = process_target(dataframe)
    
    # dataframe["target"] = dataframe["target"].str.split(", ")
    # labels = dataframe.pop('target')
    ds = tf.data.Dataset.from_tensor_slices((dict(dataframe), targets))
    if shuffle:
      ds = ds.shuffle(buffer_size=len(dataframe))
    ds = ds.batch(batch_size)
    return ds

#%% Used for testing later
def multi_label_accuracy(y_true, y_pred):
    # Convert lists to NumPy arrays for easier computation
    y_true = np.array(y_true)
    y_pred = np.array(y_pred)

    # Ensure the arrays have the same shape
    assert y_true.shape == y_pred.shape

    # Calculate element-wise equality
    match = (y_true == y_pred)

    # Calculate accuracy for each sample
    sample_accuracy = np.mean(match, axis=1)

    # Calculate average accuracy across all samples
    average_accuracy = np.mean(sample_accuracy)

    return average_accuracy
#%% Create buckets for the model
feature_columns = []

for header in ["ethnicity", "age", "height", "weight", "activity", "birth_control", "food_preferences"]:
    feature_columns.append(feature_column.numeric_column(header))
    
# bucketized age cols
age = feature_column.numeric_column('age')
age_buckets = feature_column.bucketized_column(age, boundaries=[27, 33, 42])
feature_columns.append(age_buckets)

# bucketized height cols
height = feature_column.numeric_column('height')
height_buckets = feature_column.bucketized_column(height, boundaries=[156, 165, 174])
feature_columns.append(height_buckets)

# bucketized weight cols
weight = feature_column.numeric_column('weight')
weight_buckets = feature_column.bucketized_column(weight, boundaries=[55, 66, 75])
feature_columns.append(weight_buckets)

# # indicator columns
# for col_name in ["targeted symptom"]:
#     categorical_column = feature_column.categorical_column_with_vocabulary_list(col_name, df[col_name].unique())
#     indicator_column = feature_column.indicator_column(categorical_column)
#     feature_columns.append(indicator_column)
#%% Creating the dataset
train_ds = df_to_dataset(train, shuffle = True, batch_size=batch_size)
test_ds = df_to_dataset(test, shuffle = False, batch_size=batch_size)

#%% Creating the model - change everything EXCEPT feature layer and last layer
feature_layer = tf.keras.layers.DenseFeatures(feature_columns)

model = tf.keras.Sequential([
  feature_layer,
  layers.Dense(64, activation='relu'),
  layers.Dense(128, activation='relu'),
  layers.Dense(256, activation='relu'),
  layers.Dense(256, activation='relu'),
  layers.Dense(128, activation='relu'),
  layers.Dropout(.2),
  layers.Dense(32, activation='relu'),
  layers.Dense(len(categories), activation='sigmoid')
])

#%% Compiling the model
model.compile(optimizer=tf.keras.optimizers.Adam(lr = lr),
              loss=tf.keras.losses.BinaryCrossentropy(),
              metrics=["accuracy"])

#%% Fitting the model
history = model.fit(train_ds,
          validation_data=test_ds,
          epochs=epochs)

#%% Save the model
model.save('my_model') 

#%% EVALUATION ON TEST DATASET
test_labels = []
test_ds_size = len(list(test_ds.unbatch()))
test_batch_size = len(list(test_ds))

for element in test_ds.unbatch().take(test_ds_size):
  test_labels.append(element[1].numpy())

predicted_labels = tf.cast(tf.sigmoid(model.predict(test_ds.take(test_batch_size))) > threshold, tf.int32)

#%%
print(f'{"-" * 20}EVALUATION BEGINS HERE{"-" * 20}\n')
print(f"Accuracy across ALL elements: \n\t{multi_label_accuracy(test_labels, predicted_labels) * 100}%")
print("\nNote that accuracy across all labels is a very unreliable way to measure model effectiveness.\nInstead use weighted accuracy and loss")
print('\n')
print("Precision-Recall-f1 score table as follows:\n")
print(classification_report(test_labels, predicted_labels, target_names=categories))
print("\n")
loss, accuracy = model.evaluate(test_ds)
print(f"Model loss:\n\t{loss}")
print(f"Weighted accuracy:\n\t{accuracy * 100}%\n\n")

#%% PLOT TRAINING PROCESS
def plot_history(history):
    # Plot training loss
    plt.subplot(2, 1, 1)
    plt.plot(history.history['loss'])
    plt.plot(history.history['val_loss'])
    plt.title('Model Loss')
    plt.xlabel('Epoch')
    plt.ylabel('Loss')
    plt.legend(['train', 'validation'], loc='upper right')

    # Plot training accuracy
    plt.subplot(2, 1, 2)
    plt.plot(history.history['accuracy'])
    plt.plot(history.history['val_accuracy'])
    plt.title('Model Accuracy')
    plt.xlabel('Epoch')
    plt.ylabel('Accuracy')
    plt.legend(['train', 'validation'], loc='lower right')

    # Adjust the layout to avoid overlapping of the subplots
    plt.tight_layout()
    
    # Save plot
    plt.savefig('loss-accuracy-plot.png', format='png', dpi=600)

    # Show the plot
    plt.show()

plot_history(history)

#%%
















