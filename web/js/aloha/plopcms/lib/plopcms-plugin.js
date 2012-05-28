/*!
* Aloha Editor
* Author & Copyright (c) 2011 Gentics Software GmbH
* aloha-sales@gentics.com
* Licensed unter the terms of http://www.aloha-editor.com/license.html
*/

/**
 * Aloha Plop CMS Repository
 */
define( [ 'aloha/jquery', 'aloha/repository' ],
function ( jQuery, repository ) {


    new ( repository.extend( {


        _constructor: function () {
            this._super( 'plopcms' );
        },

        /**
         * configure data as array with following format:
         *
         * [
         *   { name: 'Aloha Editor - The HTML5 Editor', url:'http://aloha-editor.com', type:'website' },
         *   { name: 'Aloha Logo', url:'http://www.aloha-editor.com/images/aloha-editor-logo.png', type:'image'  }
         * ];
         *
         * @property
         * @cfg
         */
        urlset: [],

        /**
         * Internal folder structure
         * @hide
         */
        folder: [],

        /**
         * initalize LinkList, parse all links, build folder structure and add
         * additional properties to the items
         */
        init: function () {
            this.repositoryName = sfPlopAdmin.val('aloha.repository.name');
            this.repositoryUrl = sfPlopAdmin.val('aloha.repository.url');

            this.urlset = this.getAllObjects();

            // Add ECMA262-5 Array method filter if not supported natively.
            // But we will be very conservative and add to this single array
            // object so that we do not tamper with the native Array prototype
            // object
            if ( !( 'filter' in Array.prototype ) ) {
                this.urlset.filter = function ( filter, that /*opt*/ ) {
                    var other = [],
                        v,
                        i = 0,
                        n = this.length;

                    for ( ; i < n; i++ ) {
                        if ( i in this && filter.call( that, v = this[ i ], i, this ) ) {
                            other.push( v );
                        }
                    }

                    return other;
                };
            }

//            var l = this.urlset.length;

            // generate folder structure
//            for ( var i = 0; i < l; ++i ) {
//                var e = this.urlset[ i ];
//                e.repositoryId = this.repositoryId;
//                e.id = e.id ? e.id : e.url;
//
//                var u = e.uri = this.parseUri( e.url ),
//                    // add hostname as root folder
//                    path = this.addFolder( '', u.host ),
//                    pathparts = u.path.split( '/' );
//
//                for ( var j = 0; j < pathparts.length; j++ ) {
//                    if ( pathparts[ j ] &&
//                         // It's a file because it has an extension.
//                         // Could improve this one :)
//                         pathparts[ j ].lastIndexOf( '.' ) < 0 ) {
//                        path = this.addFolder( path, pathparts[ j ] );
//                    }
//                }
//
//                e.parentId = path;
//
//                this.urlset[ i ] = new Aloha.RepositoryDocument( e );
//            }
        },

        /**
         * @param {String} path
         * @param {String} name
         * @return {String}
         */
        addFolder: function ( path, name ) {
            var type = path ? 'folder' : 'hostname',
                p = path ? path + '/' + name : name;

            if ( name && !this.folder[ p ] ) {
                this.folder[ p ] = new Aloha.RepositoryFolder( {
                    id: p,
                    name: ( name ) ? name : p,
                    parentId: path,
                    type: 'host',
                    repositoryId: this.repositoryId
                } );
            }

            return p;
        },

        /**
         * Searches a repository for object items matching query if
         * objectTypeFilter. If none is found it returns null.
         *
         * @param {Object} p
         * @param {Function} callback
         * @return {null|Array}
         */
        query: function ( p, callback ) {
            // Not supported; filter, orderBy, maxItems, skipcount, renditionFilter
            var r = new RegExp( p.queryString, 'i' );
            var d = this.urlset.filter( function ( e, i, a ) {
                return (
                    ( !p.queryString || e.name.match( r ) || e.url.match( r ) ) &&
                    ( !p.objectTypeFilter || ( !p.objectTypeFilter.length ) || jQuery.inArray( e.type, p.objectTypeFilter ) > -1 ) &&
                    true //( !p.inFolderId || p.inFolderId == e.parentId )
                );
            } );

            callback.call( this, d );
        },

        /**
         * returns the folder structure as parsed at init
         *
         * @param {Object} p
         * @param {Function} callback
         * @return {null|Array}
         */
        getChildren: function ( p, callback ) {
            var d = [],
                e;

            for ( e in this.folder ) {
                var l = this.folder[ e ].parentId;
                if ( typeof this.folder[ e ] != 'function' && ( // extjs prevention
                    this.folder[ e ].parentId == p.inFolderId || // all subfolders
                    ( !this.folder[ e ].parentId && p.inFolderId == this.repositoryId ) // the hostname
                ) ) {
                    d.push( this.folder[ e ] );
                }
            }

            callback.call( this, d );
        },

        //parseUri 1.2.2
        //(c) Steven Levithan <stevenlevithan.com>
        //MIT License
        //http://blog.stevenlevithan.com/archives/parseuri
        parseUri: function(str) {
            var	o = {
                    strictMode: false,
                    key: [ "source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
                    q: {
                        name: "queryKey",
                        parser: /(?:^|&)([^&=]*)=?([^&]*)/g
                    },
                    parser: {
                        strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
                        loose: /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
                    }
                },
                m = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
                uri = {},
                i = 14;

            while (i--) uri[o.key[i]] = m[i] || "";

            uri[o.q.name] = {};
            uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
                if ($1) uri[o.q.name][$1] = $2;
            });

            return uri;
        },

        /**
         * Get the repositoryItem with given id
         *
         * @param itemId {String} id of the repository item to fetch
         * @param callback {function} callback function
         * @return {GENTICS.Aloha.Repository.Object} item with given id
         */
        getObjectById: function ( itemId, callback ) {
            var i = 0,
                l = this.urlset.length,
                d = [];

            for ( ; i < l; i++ ) {
                if ( this.urlset[ i ].id == itemId ) {
                    d.push( this.urlset[ i ] );
                }
            }

            callback.call( this, d );

            return true;
        },

        /**
         * Retrieve all objects : links and documents
         */
        getAllObjects : function () {
          var
            result = [],
            that = this,
            queryString = ''
          ;

          // Assets
          jQuery.ajax({
            url: that.repositoryUrl,
            dataType: "json",
            contentType:"application/json",
            context: document.body,
            data: 'type=file&folder=/Assets&term=' + queryString,
            async: false, // plugin init should wait for success b4 continuing
            success: function(data) {
              jQuery.each(data, function(i, v) {
                var re = new RegExp(queryString);
                if (!v.name)
									v.name = "No name";
                if (v.url.match(re) || v.name.match(re)) {
                  result.push({
                    name: v.name,
                    type: v.type == 'image' ? v.type : 'document',
                    url: v.url
                  });
                }
              });
            }
          });

          // Links
          jQuery.ajax({
            url: that.repositoryUrl,
            dataType: "json",
            contentType:"application/json",
            context: document.body,
            data: 'type=file&folder=/Links&term=' + queryString,
            async: false, // plugin init should wait for success b4 continuing
            success: function(data) {
              jQuery.each(data, function(i, v) {
                var re = new RegExp(queryString);
                if (!v.name) {v.name = "No name";}
                if (v.url.match(re) || v.name.match(re)) {
//                  result.push(new Aloha.RepositoryDocument ({
//                    id: v.url,
//                    name: v.name,
//                    objectType: 'website',
//                    repositoryId: that.repositoryId,
//                    type: 'website',
//                    url: v.url
//                  }));
                  result.push({
                    name: v.name,
                    type: 'website',
                    url: v.url
                  });
                }
              });
            }
          });

          return result;
        }

} ) )();

} );