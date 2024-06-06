class MedicalExpert:
    def __init__(self,llmRag):
        self.llmRag = llmRag
        self.prompt = []
        self.llmAwnsers = []
        self.keywordsPatient = []
        self.keywordsDoctor = []
        self.potentialSpesialist = []

    def get_keywords(self, text):
       
        return self.llmRag.llmRAGInvoke(f"get only key words  {text}").response


    def Chat(self,prompt):
        self.prompt.append(prompt)

        prompt_patient = f"""
Hello, you will act as a medical expert, answering user questions, identifying their disease,
and recommending the appropriate specialist to manage it. Additionally, you will respond to any questions they ask you.

Here is what the patient asked: {prompt}.

here is the historical data :

Here are the prevois keywords extracted from the patient's input: {self.keywordsPatient }
Here is your prevois responses: {self.keywordsDoctor }

"""
        response = self.llmRag.llmRAGInvoke(prompt_patient).response
        findSpe = self.llmRag.llmRAGInvoke(f"""extact all the spesialists name (give me only the names) from this text {response}
                                        if you didnt find anwser with The text does not mention the name of any specialist.
                                           """).response
        
        self.potentialSpesialist.append(findSpe)
        self.llmAwnsers.append(response)

        self.keywordsPatient.append(self.get_keywords(prompt))
        self.keywordsDoctor.append(self.get_keywords(response))

        return response
