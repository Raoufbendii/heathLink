from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from medicalExpert import MedicalExpert
from langchain_google_genai import ChatGoogleGenerativeAI
from microClubChallengeGeminii import llmRAG
import logging

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["GET", "POST", "OPTIONS"],
    allow_headers=["*"],
)

llm = ChatGoogleGenerativeAI(model="gemini-1.5-pro-latest")
model = llmRAG(llm, "dataTrain")
model.setupLlmRAG()

session_expert_dict = {}

@app.post("/chat/")
async def get_response(prompt: dict):
    session_id = prompt.get("sessionID")
    if session_id in session_expert_dict:
        expert_system = session_expert_dict[session_id]
    else:
        expert_system = MedicalExpert(model)
        session_expert_dict[session_id] = expert_system

    try:
        response = expert_system.Chat(prompt.get("prompt"))
    except google.generativeai.types.generation_types.StopCandidateException as e:
        logging.error(f"StopCandidateException: {e}")
        raise HTTPException(status_code=500, detail="Internal Server Error: Model stopped response generation.")
    except Exception as e:
        logging.error(f"Unexpected error: {e}")
        raise HTTPException(status_code=500, detail="Internal Server Error: Unexpected error.")
    
    return {"response": response, "spe": expert_system.potentialSpesialist}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="127.0.0.1", port=8080)
