# predict.py
import sys
import joblib
import traceback

try:
    model = joblib.load("/home/sedra/Work/ITU_M2/Stage/predictiveAI/ITSM_predict/Python/ticket_model.pkl")
    vectorizer = joblib.load("/home/sedra/Work/ITU_M2/Stage/predictiveAI/ITSM_predict/Python/tfidf_vectorizer.pkl")

    text_input = sys.argv[1]
    text_vectorized = vectorizer.transform([text_input])

    prediction = model.predict(text_vectorized)[0]
    print(prediction)
except Exception as e:
    print("ERROR:", e)
    traceback.print_exc()