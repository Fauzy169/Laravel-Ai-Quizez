
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

def generate_quiz(kategori, jumlah, kesulitan, level):
    # Initialize LangChain LLM with Google Gemini
    gemini = ChatGoogleGenerativeAI(
        model="gemini-pro",
        google_api_key=env("GOOGLE_GEMINI"),
        temperature=0.1
    )

    # Define prompt and response format in Bahasa Indonesia
    TEMPLATE = """
    Anda adalah seorang pembuat soal ujian yang ahli, tugas Anda adalah membuat soal pilihan ganda dengan tema {subject}.
    Buatlah soal pilihan ganda untuk siswa dengan tingkat {level} dan kesulitan {tone}.
    Pastikan soal-soalnya unik, dalam bentuk teks, dan diformat seperti berikut (tanpa JSON atau format lain):
    Setiap soal harus tampak seperti ini:

    1. Teks soal
       A) Pilihan A
       B) Pilihan B
       C) Pilihan C
       D) Pilihan D
       Jawaban Benar: Pilihan yang benar

    Ulangi format ini sebanyak {number} soal.
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
        "quiz": f"Pertanyaan Quiz:\n{quiz_text}",
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
