import sys
import joblib

# Get the input text from command line argument
if len(sys.argv) < 2:
    print("No input text provided")
    sys.exit(1)

text = sys.argv[1]

# Load the saved model and vectorizer
model = joblib.load("/home/sedra/Work/ITU_M2/Stage/predictiveAI/ITSM_predict/Python/ticket_model_cat.pkl")
vectorizer = joblib.load("/home/sedra/Work/ITU_M2/Stage/predictiveAI/ITSM_predict/Python/tfidf_vectorizer_cat.pkl")

# Transform input text
X_input = vectorizer.transform([text])

# Predict category
pred_encoded = model.predict(X_input)[0]

# Load LabelEncoder to decode the category
le = joblib.load("/home/sedra/Work/ITU_M2/Stage/predictiveAI/ITSM_predict/Python/label_encoder.pkl")  # Save this after training
pred_category = le.inverse_transform([pred_encoded])[0]

# Output the predicted category
print(pred_category)
