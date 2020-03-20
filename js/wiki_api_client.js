/**
 * Calls the wiki API in order to retrieve a number of wiki pages that are relevant to the current page
 *
 */


// Attach an event listener to our File input, in order to start compressing and uploading files:
var APILinks = $("#wiki_api_link");

if (APILinks.length > 0)
{
    var APIurl = APILinks[0].href;

    performWikiApiRequest(APIurl, APILinks[0].dataset.srsearch);
    lookupTagDescription(APIurl);
}

APILinks = $("#wiki_api_link_tagdescription");

if (APILinks.length > 0)
{
    var APIurl = APILinks[0].href;

    addTagDescription(APIurl, APILinks[0].dataset.srsearch)
}

function performWikiApiRequest(APIurl, srsearch)
{
    var targetURL = APIurl.replace(/api.php.*/, 'wiki/');

    var xhr = new XMLHttpRequest();

    // When the ajax request is completed, we can parse the data sent back
    xhr.onload = function() {
        var results = xhr.response;

        if (results && results.query.search.length > 0)
        {
            var parentUL = document.getElementById("wiki_widget_ul");

            $("#wiki_widget_foundquestions_title").show();

            results.query.search.forEach(element => {
                var link = document.createElement('a');
                link.textContent = element.title;
                link.href = targetURL + element.title;

                var li = document.createElement('li');
                li.className = "qa-related-q-item";
                li.appendChild(link);
                parentUL.appendChild(li);
            });
        }
        else
            $("#wiki_widget_noquestions_title").show();
    }

    // Prepare the ajax request, using GET
    xhr.open("GET", APIurl + '?action=query&list=search&format=json&srsearch=' + srsearch);

    xhr.responseType = 'json';
    xhr.send();
}

function lookupTagDescription(APIurl)
{
    // https://pratiques.dev.tripleperformance.fr/api.php?action=query&prop=extracts&exchars=500&exlimit=1&titles=muscaris&explaintext=1&formatversion=2&exsectionformat=plain&format=json
    var links = $('.qa-tag-link');

    links.each(function(index) {

        var xhr = new XMLHttpRequest();
        var aTag = this;

        // When the ajax request is completed, we can parse the data sent back
        xhr.onload = function() {
            var results = xhr.response;

            if (results && results.query.pages.length > 0)
            {
                results.query.pages.forEach(element => {
                    if (!element.missing)
                        aTag.title = element.extract.replace(/\n[\n]+/gm, "\n");
                });
            }
        }

        // Prepare the ajax request, using GET
        xhr.open("GET", APIurl + '?action=query&prop=extracts&exchars=250&exlimit=1&explaintext=1&format=json&formatversion=2&exsectionformat=plain&titles=' + aTag.dataset.taglc);

        xhr.responseType = 'json';
        xhr.send();
    });
}

function addTagDescription(APIurl, tagName)
{
    var xhr = new XMLHttpRequest();
    var aTag = this;

    // When the ajax request is completed, we can parse the data sent back
    xhr.onload = function() {
        var results = xhr.response;

        if (results && results.query.pages.length > 0)
        {
            results.query.pages.forEach(element => {
                if (!element.missing)
                {
                    var p = $('#wiki_see_more_p')[0];

                    var newP = document.createElement('p');
                    newP.className = "wiki_description";
                    newP.innerHTML = element.extract.replace(/\n[\n]+/gm, "\n\n").trim().replace(/\n/gm, "<br/>");

                    p.parentElement.insertBefore(newP, p);
                }
            });
        }
    }

    // Prepare the ajax request, using GET
    xhr.open("GET", APIurl + '?action=query&prop=extracts&exchars=150&exlimit=1&format=json&formatversion=2&explaintext=1&exsectionformat=plain&titles=' + tagName);

    xhr.responseType = 'json';
    xhr.send();


}