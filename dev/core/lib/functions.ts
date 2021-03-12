import ko from "knockout";

export function UUID(length=8) {
  return ((new Date).getTime().toString(16)+Math.floor(1E7*Math.random()).toString(16)).substr(-length)
}

export function pluralize(str) {
  let plural = str
  switch (str[str.length-1]) {
    case 'y':
      plural = plural.replace(/(y)$/,'ies')
      break;
    case 's':
      break;
    default:
      plural += 's'
      break;
  }
  return plural
}

declare global {
  interface String {
    capitalize: () => string;
  }
  interface Window {
    UUID: () => string;
  }
}

window.UUID = UUID

String.prototype.capitalize = function() {
  return this.charAt(0).toUpperCase() + this.slice(1)
}

ko.bindingHandlers.translate = {
  update: function(element:HTMLElement, valueAccessor) {
    element.innerText = (ko.utils.unwrapObservable(valueAccessor()) || '')
      .capitalize()
      .replace('_id','')
      .replace('_',' ')
  }
}