// see http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
  var agt   = navigator.userAgent.toLowerCase();
  var is_ie = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));
  var isDOM = (document.getElementById ? true : false);
  var isIE4 = ((document.all && !isDOM) ? true : false);
  var isNS4 = (document.layers ? true : false);

  var resultwindowheight = 700;
//  var resultwindow = window.open( 'empty.html', 'menuresults', 'width=500,height=700,scrolling=1' );

  function outputResults( string ) {
    //document.write(string + '<br>' );
/*    if( resultwindow && !resultwindow.closed ) {
      resultwindow.document.write( string +'<br>' );
      resultwindowheight = resultwindowheight + 30;
      resultwindow.scroll( 0, resultwindowheight );
    }  */
  }

  // returns whether the given item
  // is of the given CSS class
  function hasClass( item, name ) {
    if( !item.className )
      return false;

    var classes = item.className.split(" ");

    for( var i = 0; i < classes.length; i++ )
      if( classes[i] == name )
        return true;

    return false;
  }

  // adds a CSS class to an item
  function addClass( item, name ) {
    item.className += " " + name;
  }

  // removes a CSS class from an item
  function removeClass( item, name ) {
    var classes = item.className.split(" ");
    var newclasses = new Array();

    for( var i = 0; i < classes.length; i++ )
      if( classes[i] != name ) {
	    /* Array.push isn't supported in IE5 */
        newclasses[newclasses.length] = classes[i];
      }

    item.className = newclasses.join(" ");
  }

  // return the absolute coordinates of an item
  function getAbsCoords( item ) {
    this.x = item.offsetLeft;
    this.y = item.offsetTop;

    outputResults( item.tagName +' '+ item.id  +' voor omhoog in dom: x = ' + this.x +', y: '+ this.y );

    outputResults( 'offsetParent gevonden: '+ item.offsetParent + ( item.offsetParent ? ' ( '+ item.offsetParent.tagName +', position: '+ item.offsetParent.style.position +' )' : '' ) );

    if( item.offsetParent != null ) { // && item.offsetParent.style.position != "absolute" 
      
      outputResults( 'going UP: de abs coordinaten vinden van: '+ item.offsetParent.tagName +' '+ item.offsetParent.id );

	    // add parent coordinates if it is relatively positioned
      parentCoords = new getAbsCoords( item.offsetParent );

      this.x += parentCoords.x;
      this.y += parentCoords.y;
    }

    outputResults( item.tagName +' '+ item.id  +' na omhoog in dom: x = ' + this.x +', y: '+ this.y );
  }

  function isUndefined(property) {
    return (typeof(property) == "undefined");
  }

  // returns the maximum allowed coordinates
  function getWindowSize() {
    var x = 0, scrollX = 0, scrollY = 0, height = 0, width = 0;

    if( document.documentElement.clientHeight ) {
      // IE 6
      scrollX = document.documentElement.scrollLeft;
      scrollY = document.documentElement.scrollTop;

      width   = document.documentElement.clientWidth;
      height  = document.documentElement.clientHeight;
    } else if( document.body.clientHeight ) {
      // IE 5.5 / 6+quirks
      scrollX = document.body.scrollLeft;
      scrollY = document.body.scrollTop;

      width   = document.body.clientWidth;
      height  = document.body.clientHeight;
    } else {
      // NS
      scrollX = window.scrollX;
      scrollY = window.scrollY;

      width   = window.innerWidth;
      height  = window.innerHeight;
    }

    this.x = scrollX + width;
    this.y = scrollY + height;
  }

  // makes a set of coordinates destined for an item
  // such that the item fits on screen
  function fitCoords( coords, item ) {
    var maxcoords = new getWindowSize();

    if( coords.x + item.offsetWidth > maxcoords.x ) {
        coords.x = Math.max( 0, maxcoords.x - item.offsetWidth );
    }

    if( coords.y + item.offsetHeight > maxcoords.y ) {
        coords.y = Math.max( 0, maxcoords.y - item.offsetHeight );
    }
  }

  function getParentWithTagName( item, tagname ) {
    if( !item )
       return null;

    if( item.tagName == tagname ) {
       return item;
    }

    return getParentWithTagName( item.parentNode, tagname );
  }

  // find the submenu-div of an item (if one)
  function getSubmenu_hor( item ) {
    return document.getElementById(item.id+"_hor");
  }
  function getSubmenu( item ) {
    /*
    var children = item.childNodes;

    for( var i = 0; i < children.length; i++ ) {
       var child = children[i];

       if( hasClass( child, "submenu" ) ) {
          return child;
       }
    }

    return null;
    */

    return document.getElementById(item.id+"_sub");
  }

  // recursively close all open submenus of an item
  function foldSubmenus( item ) {
    if( !item )
      return;

    var children = item.rows;
    var submenu;

    for( var i = 0; i < children.length; i++ ) {
       var child = children[i];

       if( getParentWithTagName( child, "TABLE" ) != item )
               continue;

       if( hasClass( child, "activeitem" ) ) {
          submenu = getSubmenu( child );
          submenu_hor = getSubmenu_hor( child );

          if( submenu ) {
            submenu.style.display = "none";
            foldSubmenus( submenu );
          }
          if( submenu_hor ) {
            submenu_hor.style.display = "none";
            foldSubmenus( submenu_hor );
            foldSubmenusc( submenu_hor );
          }

          removeClass( child, "activeitem" );

          // there is only one active item per menu
          break;
       }
    }
  }

  function foldSubmenusc( item ) {
    if( !item )
      return;

    var children = item.rows[0].cells;
    var submenu;

    for( var i = 0; i < children.length; i++ ) {
       var child = children[i];

       if( getParentWithTagName( child, "TABLE" ) != item )
               continue;

       if( hasClass( child, "activeitem" ) ) {
          submenu = getSubmenu( child );
          submenu_hor = getSubmenu_hor( child );

          if( submenu ) {
            submenu.style.display = "none";
            foldSubmenusc( submenu );
            foldSubmenus( submenu );
          }
          if( submenu_hor ) {
            submenu_hor.style.display = "none";
            foldSubmenus( submenu_hor );
          }
          removeClass( child, "activeitem" );

	  // there is only one active item per menu
          break;
       }
    }
  }

  function foldMainMenu() {
    var mainmenu = document.getElementById("mainmenu");

    foldSubmenusc( mainmenu );
    foldSubmenus( mainmenu );
  }

  var timerID;

  function refreshTimer() {
    stopTimer();

    timerID = setTimeout( "foldMainMenu()", 1000 );
  }

  function stopTimer() {
    if( timerID ) clearTimeout( timerID );
  }

  // unfold a submenu
  function unfoldSubmenu( coords, submenu ) {
    fitCoords( coords, submenu );

    submenu.style.position = "absolute";
    submenu.style.left = coords.x + "px";
    submenu.style.top  = coords.y + "px";
    submenu.style.display  = "block";
 }

 function activateMenuItem( event, item ) {
    var curTable       = getParentWithTagName( item, "TABLE" );
    var aparentName    = curTable.id;
    var submenu        = getSubmenu( item );
    var submenu_hor    = getSubmenu_hor( item );

    stopTimer();

    aparentName = aparentName.replace("/_hor$/","");
    aparentName = aparentName.replace("/_sub$/","");
    parentMenuItem = document.getElementById( aparentName );
    if( parentMenuItem ) {
      foldSubmenus( getParentWithTagName( parentMenuItem, "TABLE" ) );
      foldSubmenusc( getParentWithTagName( parentMenuItem, "TABLE" ) );
    }

    addClass( item, "activeitem" );

    if( submenu_hor ) {
    // move submenu to wanted position
      coords = new getAbsCoords( item );
	  // move next to current menu
        coords.x += item.offsetWidth;
        // patch for IE: substract border size of table
        coords.y--;
      unfoldSubmenu( coords, submenu_hor );
    } 
    if ( submenu ) {
      coords = new getAbsCoords( item );
        coords.x ++;
        coords.y += item.offsetHeight;
      unfoldSubmenu( coords, submenu );
    }

    if( is_ie ) {
      window.event.cancelBubble = true;
    } else {
      event.stopPropagation();
    }
 }
