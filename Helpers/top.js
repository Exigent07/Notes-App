const { JSDOM } = require('jsdom');

const url = 'http://localhost/bi0s/tips.html';

async function getTop() {
    const response = await fetch(url);
    const text = await response.text();
    const DOM = new JSDOM(text);
    const document = DOM.window.document;
    const top = document.querySelectorAll('button');

    console.log(top);
}

getTop();