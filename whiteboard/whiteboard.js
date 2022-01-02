// Creation of new columns
function addNewColumn() {
    // Obtains the important rows
    const headers = document.getElementById("head-row");
    const body = document.getElementById("body-row");

    // Adds a new header
    var header = document.createElement("th");
    headers.append(header);

    // Adds editable content to the header
    var editableContent = document.createElement("div");
    editableContent.classList.add("header");
    editableContent.append(document.createTextNode("New column"));
    editableContent.setAttribute("contentEditable", "true");
    editableContent.addEventListener("input", function() {
        // Aqui se impreme cuando se edita todo
        console.log(editableContent.innerHTML);
    });
    header.append(editableContent);

    // Adds a delete button along
    var deleteButton = document.createElement("button");
    deleteButton.append(document.createTextNode("X"));
    deleteButton.onclick = function(event) {
        deleteColumn(header);
    }
    header.append(deleteButton);

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
        createNewNote(stickyArea);
    }
    data.append(addNoteButton);

    body.append(data);
}

// Deletes columns along with its notes
function deleteColumn(columnHeader) {
    // Obtains the index of the column

    var headers = columnHeader.parentNode;
    var index = Array.prototype.indexOf.call(headers.children, columnHeader);
    // Obtains the data row
    var body = document.getElementById("body-row");

    // Removes the column
    body.removeChild(body.children[index]);
    headers.removeChild(headers.children[index]);
}

// Function to create new notes in a specific area
function createNewNote(stickyArea) {

    var stickyNote = document.createElement("div");
    
    // Adds all the corresponing classes to the note
    stickyNote.classList.add("draggable");
    stickyNote.classList.add("sticky-note");
    stickyNote.setAttribute("draggable", "true");

    // Adds the text area
    var textArea = document.createElement("p");
    textArea.append(document.createTextNode("New note!"));
    textArea.setAttribute("contentEditable", "true");
    stickyNote.append(textArea);
    
    // Creates the color changing input
    var colorPicker = document.createElement("input");
    colorPicker.setAttribute("type", "color");
    colorPicker.addEventListener("change", (e) => {
        stickyNote.style.backgroundColor = e.target.value;
    });
    stickyNote.append(colorPicker);

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
        }
    }, 8);
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