window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

const speechRecognition = new window.SpeechRecognition();
speechRecognition.interimResults = true;

// Transforms the result from the speech recognition
speechRecognition.addEventListener('result', (e) => {
    const text = Array.from(e.results)
        .map(result => result[0])
        .map(result => result.transcript)
        .join("");
    
    if (e.results[0].isFinal) {
        runCommand(text);
    }
}); 

// Pauses speech
function pauseSpeech() {
    if (speechSynthesis.speaking) {
        speechSynthesis.pause();
    }
}

// Continues speech
function continueSpeech() {
    if (speechSynthesis.paused) {
        speechSynthesis.resume();
        
    }
}


// Start the speech recognition cycle
speechRecognition.addEventListener('end', () => {
    speechRecognition.start();
})

speechRecognition.start();

// Function that recognizes the commands
function runCommand(command) {
    const cmd = command.toLowerCase();

    // Comando para leer la pizarra
    if (cmd.localeCompare('lee la pizarra') == 0) {
        readBoard();
    } else if (cmd.localeCompare('pausa') == 0) {
        pauseSpeech();
    } else if (cmd.localeCompare('continuar') == 0) {
        continueSpeech();
    }

    else {
        console.log("failed " + command + ".");
    }
}

function readBoard() {
    let string = "";

    const headers = [...document.getElementsByTagName("th")];
    const columns = [...document.getElementsByClassName("sticky-area")];
        
    // Headers & Columns have the same length
    for (let i = 0; i < headers.length; i++) {

        let currentHeader = headers[i]; 

        // Obtains the listen button from the header 
        string += "Columna número " + (i + 1) + " titulada: ";
        string += currentHeader.getElementsByTagName('div')[0].textContent + ". ";

        // Obtains each note
        const currentColumn = columns[i];
        const notes = currentColumn.getElementsByClassName("sticky-note");

        // Reads every note
        for (let i = 0; i < notes.length; i++) {
            const note = notes[i];
            string += note.getElementsByTagName("p")[0].textContent + ". ";
            
        }
    }
    speak(string);
}

// Talks to the user
function speak(text) {
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.rate = 1;
    speechSynthesis.speak(utterance);
}