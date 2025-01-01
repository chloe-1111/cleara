# PCinfOS

1. Setting up website:
- Open DEMO File in VSC
- Run Docker Engine
- run "docker compose up"
- Open localhost 8080:80
- Backend is connected with Frontend, AI, Frontend and Backend are dockerized
  
2. Frontend_AI:
- AI is connected to the frontend and dockerized, however, in the "suggestions" page it doesn't show any recommendations as it is not connected to the backend database, with the input data.
- To run the frontend and AI, you will need xampp with apache started
- Note:  Since we created this app for users on smartphones, we used developer tools in chrome, and selected a phone size to get an idea of what the app would look like for the users. We encourage you to do the same.  

3. AI model:]
   # Model Execution Instructions

This repository contains code for running an AI model for PCOS prediction. The model predicts labels based on input data provided in a CSV file. Follow the instructions below if you want to run the model and evaluate its performance.

## Prerequisites

- Python 3.x
- TensorFlow
- NumPy
- pandas
- scikit-learn

## Usage

- Prepare your input data:
Create a CSV file containing the input data for prediction.
Ensure that the CSV file has the following columns: ethnicity, height, weight, age, activity, birth_control, food_preferences, and target.
The target column should contain the labels to be predicted, separated by commas.
- Run the model:
Copy code
python run_model.py path/to/your/input_data.csv
Replace path/to/your/input_data.csv with the actual path to your CSV file.
- Model Output:
The model will print the predicted labels and performance metrics to the console.
The predicted labels will be displayed as a list of labels for each input instance.
The accuracy and classification report will provide insights into the model's performance.

## Evaluating Model Performance Separately 

- If you want to evaluate the model's performance separately and save the results to a file, you can use the evaluate_model.py script:
Copy code
python evaluate_model.py path/to/your/input_data.csv path/to/your/trained_model.h5
Replace path/to/your/input_data.csv with the path to your CSV file.
Replace path/to/your/trained_model.h5 with the path to your trained model file.
The evaluate_model.py script will calculate the model's performance metrics and save them to a file named model_performance.txt.

- Additional Notes

Adjust the threshold value in the code if necessary. The default threshold is set to 0.6.
Make sure to provide the correct file paths and ensure the CSV file is properly formatted with the required columns.
