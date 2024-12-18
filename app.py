import json
from flask import Flask, request, jsonify
from langchain.prompts import PromptTemplate
from langchain.chains import LLMChain, SequentialChain
from langchain_google_genai import ChatGoogleGenerativeAI
import environ

# Load environment variables
env = environ.Env()
environ.Env.read_env('.env')

app = Flask(__name__)
# Bahasa Inggris
def generate_quiz(kategori, jumlah, kesulitan, level):
    # Initialize LangChain LLM with Google Gemini
    gemini = ChatGoogleGenerativeAI(
        model="gemini-pro",
        google_api_key=env("GOOGLE_GEMINI"),
        temperature=0.1
    )

    # Define prompt and response format
    TEMPLATE = """
    You are an expert MCQ maker, assigned to create questions and answers using multiple choice questions.
    Create multiple choice questions with {subject} theme intended for {level} students with {tone} difficulty.
    Ensure that the questions are unique, in text form, and formatted as follows (no JSON formatting):
    Each question should look like this:

    1. Question text
       A) Option A
       B) Option B
       C) Option C
       D) Option D
       Correct Answer: Correct option

    Repeat the format for {number} questions.
    """
    
    quiz_prompt = PromptTemplate(
        input_variables=["subject", "tone", "number", "level"],
        template=TEMPLATE
    )
    
    quiz_chain = LLMChain(llm=gemini, prompt=quiz_prompt, output_key="quiz", verbose=True)

    generate_eval = SequentialChain(
        chains=[quiz_chain],
        input_variables=["subject", "tone", "number", "level"],
        output_variables=["quiz"],
        verbose=True
    )

    result = generate_eval({
        "subject": kategori,
        "tone": kesulitan,
        "number": jumlah,
        "level": level,
    })

    # Clean up the response by formatting the quiz properly
    quiz_text = result["quiz"]

    return {
        "level": level,
        "number": jumlah,
        "quiz": f"Quiz Questions:\n{quiz_text}",
        "subject": kategori,
        "tone": kesulitan
    }

@app.route('/generate-quiz', methods=['POST'])
def generate_quiz_api():
    data = request.get_json()
    kategori = data.get('kategori')
    jumlah = data.get('jumlah')
    kesulitan = data.get('kesulitan')
    level = data.get('level')

    response = generate_quiz(kategori, jumlah, kesulitan, level)
    return jsonify(response)

if __name__ == "__main__":
    app.run(debug=True)
