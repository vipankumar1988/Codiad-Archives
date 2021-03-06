/*
 * Copyright (c) Codiad & Rafasashi, distributed
 * as-is and without warranty under the MIT License.
 * See http://opensource.org/licenses/MIT for more information. 
 * This information must remain intact.
 */

(function(global, $){
    
    var codiad = global.codiad,
        scripts = document.getElementsByTagName('script'),
        path = scripts[scripts.length-1].src.split('?')[0],
        curpath = path.split('/').slice(0, -1).join('/')+'/';

    $(function() {
        codiad.Extract.init();
    });

    codiad.Extract = {
        
        path: curpath,
        file: "",
        
        init: function() {
            
        },
        
        //////////////////////////////////////////////////////////
        //
        //  Show dialog to enter new epath
        //
        //  Parameter
        //
        //  path - {String} - File path
        //
        //////////////////////////////////////////////////////////
        showDialog: function(path) {
            this.file = path;
            var epath = this.getName(path);
            codiad.modal.load(400, this.path+"dialog.php?path="+path+"&epath="+epath);
        },
        
        //////////////////////////////////////////////////////////
        //
        //  Extract file
        //
        //  Parameter
        //
        //  path - {String} - File path
        //  epath - {String} - Archive path
        //
        //////////////////////////////////////////////////////////
        extract: function(path, epath) {
            var _this = this;
            if (typeof(path) == 'undefined') {
                path = this.file;
            }
            if (typeof(epath) == 'undefined') {
                epath = $('#extract_path').val();
                codiad.modal.unload();
            }
	// console.log(_this.path+"controller.php?action=extract&path="+path+"&epath="+epath);
            $.getJSON(_this.path+"controller.php?action=extract&path="+path+"&epath="+epath, function(json){
                codiad.message[json.status](json.message);
                codiad.filemanager.rescan(codiad.project.getCurrent());
            });
        },
        
        //////////////////////////////////////////////////////////
        //
        //  Get basename of file
        //
        //  Parameter
        //
        //  path - {String} - File path
        //
        //////////////////////////////////////////////////////////
        getName: function(path) {
            return path.substring(path.lastIndexOf("/")+1);
        }
    };
})(this, jQuery);
