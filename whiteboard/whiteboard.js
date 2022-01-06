// Deletes columns along with its notes
function deleteColumn(columnHeader) {
    // Obtains the index of the column
    var headers = columnHeader.parentNode;
    var index = Array.prototype.indexOf.call(headers.children, columnHeader);

    // Obtains the data row
    var body = document.getElementById("body-row");
    let footers = document.getElementById("foot-row");


    // Removes the column
    body.removeChild(body.children[index]);
    headers.removeChild(headers.children[index]);
    footers.removeChild(footers.children[index]);
    updateWFStates();
}

// Function to create new notes in a specific area
function createNewNote(stickyArea,{text,color,height,width}) {
    var stickyNote = document.createElement("div");
    stickyNote.style.backgroundColor = color;
    stickyNote.style.height = height;
    stickyNote.style.width = width;
    
    // Adds all the corresponing classes to the note
    stickyNote.classList.add("draggable");
    stickyNote.classList.add("sticky-note");
    stickyNote.setAttribute("draggable", "true");

    // Adds the text area
    var textArea = document.createElement("p");
    textArea.append(document.createTextNode(text));
    textArea.setAttribute("contentEditable", "true");
    stickyNote.append(textArea);

    var timer;
    textArea.addEventListener("keyup", function(event) {
        clearTimeout(timer);
        if (event) {
            timer = setTimeout( () => {
                // Updates the backend 
                updateWFStates();    
            },
            3000);
        }        
    });
    

    // Creates the color changing input
    var colorPicker = document.createElement("input");
    colorPicker.setAttribute("type", "color");
    colorPicker.addEventListener("change", (e) => {
        stickyNote.style.backgroundColor = e.target.value;
         // Updates the backend 
         updateWFStates();
    });
    stickyNote.append(colorPicker);

    // Creates speech button
    var speech = document.createElement("button");
    speech.append(document.createTextNode("Listen"));
    speech.addEventListener("click", (e) => {
        const text = textArea.textContent;
        // Function in "speech.js"
        speak(text);
    });
    stickyNote.append(speech);

    // Creates the delete button
    var deleteButton = document.createElement("button");
    deleteButton.append(document.createTextNode("Delete"));
    deleteButton.addEventListener("click", (e) => {
        var steps = 0;
        var timer = setInterval(function () {
            steps++;
            stickyNote.style.opacity -= 0.05 * steps;
            if (steps >= 20) {
                clearInterval(timer);
                timer = undefined;
                stickyNote.remove();
                // Updates the backend 
                updateWFStates();
            }
        }, 13);
    });

    stickyNote.append(deleteButton);

    // Adds the events for dragging
    stickyNote.addEventListener('dragstart', () => {
        stickyNote.classList.add('dragging');
    });

    stickyNote.addEventListener('dragend', () => {
        stickyNote.classList.remove('dragging');
        // Update backend
        updateWFStates();
    });    


    // Changes the visibility of the note
    stickyNote.style.opacity = 0;

    // Adds the note to the stickyArea
    stickyArea.append(stickyNote);

    // Slowly fades in the sticky note
    var steps = 0;
    var timer = setInterval(function () {
        steps++;
        stickyNote.style.opacity = 0.05 * steps;
        if (steps >= 20) {
            clearInterval(timer);
            timer = undefined;

            //Update the backend
            updateWFStates();
        }
    }, 8);
}

// Loads the workflow
function loadWorkflow() {
    const workflowComboBox = document.getElementById("workflowsCombo");
    const selectedWorkflow = workflowComboBox.options[workflowComboBox.selectedIndex].text;
    
    //back 

    document.getElementById("head-row").innerHTML = "";
    document.getElementById("body-row").innerHTML = "";
    document.getElementById("foot-row").innerHTML = "";

    var user = "lisethGonz6"
    loadStates(user,selectedWorkflow);
}

// Creation of new columns
function addNewColumn(name) {

    // Obtains the important rows
    const headers = document.getElementById("head-row");
    const body = document.getElementById("body-row");
    const foot = document.getElementById("foot-row");

    // Adds a new header
    var header = document.createElement("th");
    headers.append(header);

    // Adds editable content to the header
    var editableContent = document.createElement("div");
    editableContent.classList.add("header");
    editableContent.append(document.createTextNode(name));
    editableContent.setAttribute("contentEditable", "true");

    var timer;
    editableContent.addEventListener("keyup", function(event) {
        clearTimeout(timer);
        if (event) {
            timer = setTimeout( () => {    
                // Conexion al backend para actualizar header
                const index = [...headers.children].indexOf(header);
                // Updates the backend 
                updateWFStates();   
            },
            1500);
        }     
    });

    header.append(editableContent);

    // Adds a delete button along
    var deleteButton = document.createElement("button");
    deleteButton.append(document.createTextNode("X"));
    deleteButton.onclick = function(event) {
        deleteColumn(header);
    }
    header.append(deleteButton);

    // Adds a listen button for TTS
    var speech = document.createElement("button");
    speech.append(document.createTextNode("Listen"));
    speech.addEventListener('click', (e) => {
        const text = editableContent.textContent;

        // Function in "speech.js"
        speak(text);
    });
    header.append(speech);

    // Adds a new body column
    var data = document.createElement("td");

    // Adds the sticky note area to the column
    var stickyArea = document.createElement("div");
    stickyArea.classList.add('sticky-area');

    // Adds the corresponding event to the sticky area
    stickyArea.addEventListener('dragover', e => {
        e.preventDefault();
        const itemPos = positionElement(stickyArea, e.clientY);
        const draggable = document.querySelector('.dragging');
        if (itemPos != null) {
            stickyArea.insertBefore(draggable, itemPos);
        } else {
            stickyArea.appendChild(draggable);
        }
    });

    data.append(stickyArea);

    // Adds the add new note button to the column
    var addNoteButton = document.createElement("button");
    addNoteButton.append(document.createTextNode("Add Note"));
    addNoteButton.onclick = function(event) {
        createNewNote(stickyArea,{'color' : '#FFFF00','text' : 'New Sticky!!','height' : '97px','width' : '262px'});
    }
    let footer = document.createElement("td");
    footer.append(addNoteButton);
    foot.append(footer);


    body.append(data);

    let pos_options = document.getElementById("st_position").childNodes.length;
    console.log(pos_options);
    if(pos_options>0)
    {
        let pos_comboBox = document.getElementById("st_position")
        const selectedPosition = pos_comboBox.options[pos_comboBox.selectedIndex].text;

        let headers_row = [...document.getElementById("head-row").children];
        let bodies_row = [...document.getElementById("body-row").children];
        let footers_row = [...document.getElementById("foot-row").children];

        console.log(headers_row);
    }

    

    // Updates the backend 
    updateWFStates();

    return stickyArea;
}


// Adds the note in the corresponding area
function positionElement(stickyArea, posY) {
    const draggables = [...stickyArea.querySelectorAll('.draggable:not(.dragging)')];

    return draggables.reduce((closest, child) => {
        const rect = child.getBoundingClientRect();
        const offset = posY - rect.top - rect.height / 2;
        if (offset < 0 && offset > closest.offset) {
            return {offset: offset, element: child};
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY}).element;
}

// Adds the note in the corresponding area
function updatePos(stickyArea, posY) {

    let stikies = document.getElementsByClassName('sticky-area').children;
    console.log(stikies);
}