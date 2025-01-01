import sys
import tensorflow as tf
import numpy as np
import pandas as pd

# Check if the correct number of command-line arguments is provided
if len(sys.argv) < 3:
    print("Usage: python run_model.py <csv_file_path> <user_input>")
    sys.exit(1)

# Get the CSV file path and user input from command-line arguments
csv_file_path = sys.argv[1]
user_input = sys.argv[2]

# Constants and variables
path = csv_file_path
check_labels = True
threshold = 0.6
batch_size = 32
categories = ["spinach_1", "spinach_2", "spinach_3", "cheakpeas_1", "cheakpeas_2", "cheakpeas_3",
              "salmon_1", "salmon_2", "salmon_3", "almonds_1", "almonds_2", "almonds_3",
              "grapefruit_1", "grapefruit_2", "grapefruit_3"]

# Support functions
def process_target(df):
    df['target'] = df["target"].str.replace(' ', '')
    target = df.pop('target').to_numpy().tolist()
    for i, item in enumerate(target):
        one_hot = [0 for _ in range(15)]
        for label in item.split(','):
            one_hot[categories.index(label)] = 1
        target[i] = one_hot
    return target

def df_to_dataset(dataframe, shuffle=True, batch_size=32):
    dataframe = dataframe.copy()
    ds = tf.data.Dataset.from_tensor_slices(dict(dataframe))
    if shuffle:
        ds = ds.shuffle(buffer_size=len(dataframe))
    ds = ds.batch(batch_size)
    return ds

def multi_label_accuracy(y_true, y_pred):
    y_true = np.array(y_true)
    y_pred = np.array(y_pred)
    assert y_true.shape == y_pred.shape
    match = (y_true == y_pred)
    sample_accuracy = np.mean(match, axis=1)
    average_accuracy = np.mean(sample_accuracy)
    return average_accuracy

# Read the CSV file into a DataFrame
df = pd.read_csv(path, sep=";")
df = df[["ethnicity", "height", "weight", "age", "activity", "birth_control", "food_preferences"]]

# Convert the user input to a DataFrame row
user_input_df = pd.DataFrame([user_input], columns=df.columns)
df = pd.concat([df, user_input_df], ignore_index=True)

# Load the model
model = tf.keras.models.load_model("C:\\xampp\\htdocs\\PCOScompanion\\PCOS_AI\\model.py")


# Create the dataset
ds = df_to_dataset(df, shuffle=False, batch_size=batch_size)

# Predict labels
predicted_labels = tf.cast(tf.sigmoid(model.predict(ds)) > threshold, tf.int32)
predicted_labels = predicted_labels.numpy()

# Convert predicted labels to final labels
final_labels = [categories[i] for i in range(len(categories)) if predicted_labels[0][i] == 1]

# Print the predicted labels
print("\nPredicted labels are as follows:")
print(final_labels)















