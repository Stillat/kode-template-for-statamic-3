(function () {
    var tocContainer = document.getElementById('toc-container');

    if (tocContainer == null) {
        return;
    }

    var headings = document.querySelectorAll('.heading-permalink'),
        wrapper = document.getElementById('toc-wrapper'),
        tocLinks = [],
        tocElement = document.createElement('ul'),
        isFirst = true,
        lastLevel = null,
        listStack = [tocElement];

    if (headings.length > 0) {
        wrapper.style.display = 'block';
    }

    for (var i = 0; i < headings.length; i++) {
        var heading = headings[i].parentElement,
            level = parseInt(heading.tagName.charAt(1)),
            innerText = heading.innerText.substring(1),
            tocEntry = document.createElement('li'),
            tocLink = document.createElement('a');

        tocLink.classList.add('toc-link');
        tocLink.href = '#' + headings[i].id;
        tocLink.innerText = innerText;
        tocEntry.appendChild(tocLink);

        tocLinks.push(tocLink);

        if (!isFirst && level > lastLevel) {
            var subList = document.createElement('ul');
            listStack[listStack.length - 1].lastChild.appendChild(subList);
            listStack.push(subList);
        }

        if (!isFirst && level < lastLevel) {
            listStack.pop();
            if (listStack.length == 0) {
                listStack.push(tocElement);
            }
        }

        listStack[listStack.length - 1].appendChild(tocEntry);

        isFirst = false;
        lastLevel = level;
    }

    tocContainer.appendChild(tocElement);

    var tocObserver = new IntersectionObserver(entries => {
        var candidates = [];

        entries.forEach(entry => {
            const id = entry.target.getAttribute('id');
            const tocLink = document.querySelector(`.toc-link[href="#${id}"]`);

            if (entry.isIntersecting) {
                candidates.push(tocLink);
            } else {
                tocLink.classList.remove('active');
            }
        });

        if (candidates.length > 0) {
            candidates[0].classList.add('active');
        }
    }, {
        rootMargin: '0px',
        threshold: 0.5
    });

    headings.forEach(heading => {
        tocObserver.observe(heading);
    });
})();