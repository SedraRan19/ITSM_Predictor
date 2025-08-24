import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.linear_model import LogisticRegression
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import classification_report, accuracy_score
import joblib

# Load dataset
df = pd.read_csv("ticket_dataset_french_1000.csv")

# Combine text fields
df["text"] = df["short_description"] + " " + df["description"]

# Encode the category labels
le = LabelEncoder()
df["category_encoded"] = le.fit_transform(df["category"])

# TF-IDF Vectorization
vectorizer = TfidfVectorizer(ngram_range=(1,2), max_features=5000)
X = vectorizer.fit_transform(df["text"])
y = df["category_encoded"]

# Train-test split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train model
model = LogisticRegression(max_iter=1000)
model.fit(X_train, y_train)

# Save model and vectorizer
joblib.dump(model, "ticket_model_cat.pkl")
joblib.dump(vectorizer, "tfidf_vectorizer_cat.pkl")
joblib.dump(le, "label_encoder.pkl")

print("✅ Model and vectorizer saved successfully!")