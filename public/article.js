document.addEventListener('DOMContentLoaded', function() {
    function getQueryString() {
        return window.location.search;
    }
    function reloadArticles() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', window.location.pathname + getQueryString(), true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var parser = new DOMParser();
                var doc = parser.parseFromString(xhr.responseText, 'text/html');
                var newList = doc.querySelector('#article-list');
                if (newList) {
                    document.querySelector('#article-list').innerHTML = newList.innerHTML;
                }
            }
        };
        xhr.send();
    }
    setInterval(reloadArticles, 10000);
}); 