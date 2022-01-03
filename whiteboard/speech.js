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
        const headers = [...document.getElementsByTagName("th")];
        const columns = [...document.getElementsByClassName("sticky-area")];
        
        // Headers & Columns have the same length
        for (let i = 0; i < headers.length; i++) {
            // Obtains the listen button from the header
            const currentHeader = headers[i];
            const listenButton = currentHeader.getElementsByTagName("button")[1];
            speak("Columna número " + (i + 1) + " titulada: ");
            listenButton.click();

            // Obtains each note
            const currentColumn = columns[i];
            const notes = currentColumn.getElementsByClassName("sticky-note");
            
            // Reads every note
            for (let i = 0; i < notes.length; i++) {
                const note = notes[i];
                note.getElementsByTagName("button")[0].click();
            }
        }

    
    } else if (cmd.localeCompare('pausa') == 0) {

    } else {
        console.log("failed " + command + ".");
    }
}

// Talks to the user
function speak(text) {
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.rate = 1;
    speechSynthesis.speak(utterance);
}