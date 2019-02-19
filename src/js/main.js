import List from './List.js';

fetch('./api/parts')
    .then(data => status(data))
    .then(data => render(data))
    .catch(error => alert(error.message));

function status(data) {
    if (data.status !== 200) {
        throw new Error(data.statusText);
    } else {
        return data.json();
    }
}

function render(data) {
    const container = document.getElementById('app');
    const list = new List(container);
    list.render(data);
}