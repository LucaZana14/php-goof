import langchain
from transformers import pipeline

# Esempio di caricamento modello da HuggingFace senza controllo di integrità
classifier = pipeline("sentiment-analysis", model="bert-base-uncased")

def analyze_input(text):
    return classifier(text)