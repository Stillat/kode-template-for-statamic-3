// This is all you.
require('./components/blogToc');
require('./components/syntaxHighlighting');

import mermaid from "mermaid";
mermaid.initialize({ startOnLoad: true, class: 'mermaid', theme: 'neutral' });

var btn = document.getElementById('nav-toggle'),
    nav = document.getElementById('responsive-nav');

btn.addEventListener("click", () => {
    nav.classList.toggle('hidden');
});

window.addEventListener('resize', function () {
    if (window.innerWidth > 768) {
        nav.classList.add('hidden');
    }
});