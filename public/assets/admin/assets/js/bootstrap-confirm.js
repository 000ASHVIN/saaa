

//This script has 2 modes

// ----------------------------------------
// 1) HANDLE BARS TEMPLATE
// ----------------------------------------
// uses the "data-confirm-template" attribute
// the template will pass in all data-attributes also it
// specially includes the href attribute

// you can use {{id}} in your template if you have an "data-id" attribute on your anchor
//   <a href="#" data-confirm-template="hb-delete-template" data-id="12345" data-name="John">Delete</a>



// ----------------------------------------
// 2) ATTRIBUTE MODE
// ----------------------------------------
// uses the follower attributes
//     data-confirm-title      =     title for modal (default "Confirm")
//     data-confirm-content    =     *required* body content
//     data-confirm-button     =     confirm button text default "Yes"
//     data-confirm-close      =     cancel button text (default "Close")


//TO USE:
//         to use this simply use an anchor tag with your target href and
//           add in a data-confirm-content attribute or a data-confirm-template
//     <a href="#" data-confirm-content="Are you sure you want to delete this">Delete</a>


(function (dataConfirm, $, undefined) {

    //MODE 1 ) HANDLE BARS TEMPLATE
    var dataConfirmTemplateAttributeName = "data-confirm-template";

    //MODE 2) INPUT TITLE AND TEXT
    var dataConfirmTitleAttributeName   = "data-confirm-title";
    var dataConfirmContentAttributeName = "data-confirm-content";
    var dataConfirmButtonAttributeName  = "data-confirm-button";
    var dataConfirmCloseAttributeName   = "data-confirm-close";

    function init() {
        $(document).ready(function () {
            //anchor tags
            $("a[" + dataConfirmTemplateAttributeName + "]").click(function (evnt) {
                evnt.preventDefault();
                show_modal_from_template(this);
            });

            $("a[" + dataConfirmContentAttributeName + "]").click(function (evnt) {
                evnt.preventDefault();
                show_modal_from_attributes(this);
            });
        });
    }


    function show_modal_from_attributes(element)
    {
        //set defaults
        var title = "Confirm";
        var content = "Are you sure?";
        var button = "Yes";
        var close = "Close";

        //set options
        if ($(element).attr(dataConfirmTitleAttributeName)) {
            title = $(element).attr(dataConfirmTitleAttributeName);
            title = html_decode(title);
        }

        if ($(element).attr(dataConfirmContentAttributeName)) {
            content = $(element).attr(dataConfirmContentAttributeName);
            content = html_decode(content);
        }

        if ($(element).attr(dataConfirmButtonAttributeName)) {
            button = $(element).attr(dataConfirmButtonAttributeName);
            button = html_decode(button);
        }
        if ($(element).attr(dataConfirmCloseAttributeName)) {
            close = $(element).attr(dataConfirmCloseAttributeName);
            close = html_decode(close);
        }


        /////////////////////////
        // BUILD MODAL
        /////////////////////////
        var modal = $("<div>").addClass("modal").addClass("fade").attr("tabindex", "-1");
        var modal_dialog = $("<div>").addClass("modal-dialog");
        var modal_content = $("<div>").addClass("modal-content");

        //header
        var modal_header = $("<div>").addClass("modal-header");
        var headerClose = $('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>');
        var modal_header_title = $("<h4>").addClass("modal-title").html(title);

        modal_header.append(headerClose);
        modal_header.append(modal_header_title);


        //body
        var modal_body = $("<div>").addClass("modal-body").html(content);

        //footer
        var modal_footer = $("<div>").addClass("modal-footer");
        var confirm_link = $("<a>").attr("href", $(element).attr("href")).addClass("btn").addClass("btn-danger").html(button);
        var close_link = $("<button>").attr("type", "button").addClass("btn").addClass("btn-default").attr("data-dismiss", "modal").html(close);

        modal_footer.append(confirm_link);
        modal_footer.append(close_link);

        modal_content.append(modal_header);
        modal_content.append(modal_body);
        modal_content.append(modal_footer);
        modal_dialog.append(modal_content);
        modal.append(modal_dialog);

        //show the modal
        $(modal).modal({ keyboard: true, show: true });
    }

    function show_modal_from_template(element) {

        if (!window.Handlebars) {
            alert("auth confirm handler error, hanldebars.js does not exists");
            console.error("auto confirm handler error, hanldebars.js does not exists");
            return;
        }

        var templateName = getTemplateName(element);

        //sanity check that template name exists
        if ($(templateName).length === 0)
        {
            alert("could not open confirm box with template name of '" + templateName + "', because it does not exist.");
            console.error("could not open confirm box with template name of '" + templateName + "', because it does not exist.");
            return;
        }


        //build the template using Handlebars
        var source = $(templateName).html();
        var template = Handlebars.compile(source);

        //get the data
        var data = getDataStructure(element);

        //get the modal html
        var modalHtml = template(data);

        //set tabindex incase it hasn't been set, so we can use ESC
        modalHtml = ensureTabIndex(modalHtml);

        //ensure that this is a bootstrap modal template
        if(! isBootstrapModal(modalHtml))
        {
            alert("Attempted to open confirm box, but this isn't a bootstrap modal.");
            console.error("Attempted to open confirm box, but this isn't a bootstrap modal.");
            return;
        }

        //show modal
        $(modalHtml).modal({ keyboard: true, show: true  });
    }

    function isBootstrapModal(html) {
        var results = $(html).hasClass("modal");
        return results;
    }

    function getTemplateName(element) {
        var templateName = $(element).attr(dataConfirmTemplateAttributeName);
        if (templateName.indexOf("#") != 0) { templateName = "#" + templateName; }
        return templateName;
    }

    function getDataStructure(element) {

        //get the context data from the element
        var data = $(element).data();

        //add href data if it doesn't already exists in the data
        if ($(element).attr("href")) {
            if (!data.href) {
                data.href = $(element).attr("href");
            }
        }

        return data;
    }

    function ensureTabIndex(html) {
        var result = html;
        result = $(result).attr("tabindex", "-1");
        return result;
    }

    function html_decode(str)
    {
        //don't decode if the value contains any '<'
        if (str.indexOf("<") > -1)
        {
            return str;
        }

        return $("<div>").html(str).text();
    }

    //execute the init function
    init();
})(dataConfirm = window.dataConfirm || {}, jQuery);