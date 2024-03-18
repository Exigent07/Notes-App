const { JSDOM } = require('jsdom');

const url = 'https://amberstudent.com/blog/post/best-note-taking-apps-every-student-needs';

async function top() {
    const response = await fetch(url, {method: "GET"});
    const text = await response.text();
    const DOM = new JSDOM(text);
    const document = DOM.window.document;

    const top = document.getElementsByClassName('rich-text-block dropdown-link-3 rich-text-faq rich-text-blog w-richtext')[0];
    
    let top_list = [];

    for (let index = 1; index <= 5; index++) {
        top_list.push(top.querySelectorAll('p')[index].innerHTML);   
    }

    console.log(top_list.join(','));
}

top();