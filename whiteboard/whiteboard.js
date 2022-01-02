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

    // Adds the corresponding data to the column
    var stickyArea = document.createElement("div");
    stickyArea.classList.add('sticky-area');
    data.append(stickyArea);

    var addNoteButton = document.createElement("button");
    addNoteButton.append(document.createTextNode("Add Note"));
    addNoteButton.onclick = function(event) {
        createNewNote();
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

// Create new note
function createNewNote() {
    console.log("New note");
}