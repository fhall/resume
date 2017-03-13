/**
 * Init function
 */
function init() {
  /** Get all elements that has the data-max-items attribute */
  var limitItemsElements = document.querySelectorAll('[data-max-items]');
  /** Run the limitElements function on the selected elements */
  limitItemsElements.forEach(limitElements);
}

/**
 * Function to limit the number of elements displayed
 * Uses two element attributes: data-max-items (integer) and data-max-items-delimiter (string, matching tags)
 */
function limitElements(element) {
  /** Get the number of items that should be displayed */
  var maxNoItems = element.getAttribute('data-max-items');
  maxNoItems = maxNoItems ? maxNoItems : 3;

  /** Get the delimiter that should be used (a tag name) */
  var delimiters = element.getAttribute('data-max-items-delimiter').split(',');
  delimiters = delimiters ? delimiters : ['h3'];
  delimiters = delimiters.map(function(delimiter) { return delimiter.toLowerCase(); });

  /** Get the elements that are the basis of the limit */
  var limitElements = element.querySelectorAll(delimiters);
  var noLimitElements = limitElements.length;

  /** If the number of elements that is the basis of the limitation are more than the limitation */
  if (noLimitElements >> maxNoItems) {

    /** Get the elements children */
    var childElements = element.children;
    var noChildElements = childElements.length;

    /** Create the wrapper element */
    var wrapperElement = document.createElement('div');
    wrapperElement.className = "maxNoItemsHidden";
    wrapperElement.style.display = 'none';

    /** Create the show more/less element */
    var showMoreElement = document.createElement('a');
    showMoreElement.innerText = 'Show more';
    showMoreElement.setAttribute('href', '#');
    showMoreElement.addEventListener('click', function(event) {
      event.preventDefault();
      if(wrapperElement.style.display == 'block') {
        wrapperElement.style.display = 'none';
        showMoreElement.innerText = 'Show more';
      } else {
        wrapperElement.style.display = 'block';
        showMoreElement.innerText = 'Show less';
      }
    });

    /** Array holding all the elements that should be wrapped */
    var elementsToWrap = [];

    /** Counter holding the number of times a delimiter tag has been traversed */
    var noDelimitedChildElements = 0;

    /** Loop through all the child elements */
    for (var i = 0; i < noChildElements; i++) {
      /** The current child element */
      var childElement = childElements[i];

      /** If the child element has a tag name and the tag name is a delimiter tag */
      if (childElement.tagName && delimiters.indexOf(childElement.tagName.toLowerCase()) != -1) {
        /** The number of delimiter elements traversed increases */
        noDelimitedChildElements++;
      }

      /** If the number of traversed delimiter tags is more than the max number of tags */
      if (noDelimitedChildElements > maxNoItems) {
        /** Add the element to the array holding nodes that should be wrapped */
        elementsToWrap.push(childElement);
      }
    }

    /** Insert show more anchor and the wrapper element and append the elements to it */
    element.insertBefore(showMoreElement, limitElements[maxNoItems]);
    element.insertBefore(wrapperElement, limitElements[maxNoItems]);

    /** Move all elements to be wrapped to the wrapper element */
    elementsToWrap.forEach(function (el) { wrapperElement.appendChild(el); });
  }
}

/** Run init function when the DOM has loaded */
document.addEventListener("DOMContentLoaded", init);
