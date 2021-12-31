var count = 0;

function createNote(column) {
    var stickyNote = document.createElement("p");
    var content = document.createTextNode("Content test" + count);
    stickyNote.append(content);
    column.prepend(stickyNote);
    count++;
}


function test(a) {
    console.log(a);
}
