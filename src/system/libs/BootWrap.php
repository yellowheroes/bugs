<?php

namespace yellowheroes\projectname\system\libs;

use yellowheroes\projectname\system\config as config;

/**
 * class bootWrap (bootstrap wrapper)
 * we use heredoc to wrap Bootstrap mark-up (components) into class methods.
 *
 * Heredoc tips: escaping
 * \n means newline
 * \r means return
 * \t means tab
 * \v means vertical tab
 * \b means backspace
 * \a means alert (beep or flash)
 *
 * this is not an exhaustive wrapper, we have selected only those components
 * we use regularly ourselves.
 *
 * the Bootstrap navigation components are custom-fitted to show active class on the correct DOM element (i.e. correct nav button)
 * the user sees based on our MVC-routing: i.e. controller/action/params
 *
 * When invoked, a function returns the Bootstrap html mark-up.
 *
 */

/**
 * Class BootWrap
 * @package yellowheroes\projectname\system\libs
 */
class BootWrap
{
    /**
     * @var string $htmlInit opening block html5 page
     * @var string $meta html meta-data (charset, viewport)
     * @var string $styles html CSS stylesheets (in <head>)
     * @var string $libs html Bootstrap libraries, other javascript libraries (in <head>)
     * @var string $js html additional javascript (libraries) and related CSS (e.g. for editor: Quill.js and snow.css in same block)  in <head>
     * @var string $other optional any other html (in <head>)
     * @var string title        browser tab title
     * @var string footer       closing block html5 page
     */
    private $htmlInit = '';
    private $meta = '';
    private $styles = '';
    private $libs = '';
    private $js = '';
    private $other = '';
    private $title = '';
    private $footer = '';

    /**
     * BootWrap constructor
     */
    public function __construct()
    {
        // set default html5 and Bootstrap elements
        $this->setHtmlInit();
        $this->setMeta();
        $this->setStyles();
        $this->setLibs();
        $this->setTitle();
        $this->setFooter();
    }

    /**
     * setters to build the html5 document:
     *          setHtmlInit()                           - document open
     *          setMeta(), setStyles(), setLibs()       - <head> </head> section
     *          setTitle()                              - browser-tab title
     *              ...
     *              nav-bar                             - defined in header.php
     *              document body                       - each view-page renders the core content of the page
     *              ...
     *          setFooter()                             - document close
     */

    /**
     * set the opening block for html5 page
     * @return void
     */
    public function setHtmlInit(): void
    {
        $this->htmlInit = <<<HEREDOC
<!doctype html>
<html lang="en">
    <head>
HEREDOC;
    }

    /**
     * set the required html meta tags (in <head> block)
     * @return void
     */
    public function setMeta(): void
    {
        $this->meta = <<<HEREDOC
<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
HEREDOC;
    }

    /**
     * set CSS stylesheets (in <head> block)
     * @param array $styleSheets defines path to each style sheet
     * @return void
     */
    public function setStyles($styleSheets = []): void
    {
        // default CSS (minimum requirement for Bootstrap to function)
        // the first call to setStyles() is on __construct(), with $styleSheets empty, so always get  default stylesheet
        if (empty($styleSheets)) {
            $this->styles = <<<HEREDOC
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">\n
HEREDOC;
        } else {
            // additional CSS
            foreach ($styleSheets as $key) {
                $this->styles .= <<<HEREDOC
        <link rel="stylesheet" href="$key">\n
HEREDOC;
            }
        }
    }

    /**
     * set libraries that need to be referenced to enable Bootstrap (in <head> block)
     * @param array $libs
     * @return void
     */
    public function setLibs($libs = []): void
    {
        // default libraries (minimum requirement for Bootstrap to function)
        // the first call to setLibs() is on __construct(), with $libs empty, so always get  default libs
        if (empty($libs)) {
            $this->libs = <<<HEREDOC
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>\n\n
HEREDOC;
        } else {
            // additional libraries
            foreach ($libs as $key) {
                $this->libs .= <<<HEREDOC
<script src="$key"></script>\n
HEREDOC;
            }
        }
    }

    /**
     * set any additional javascript (libraries) (in <head> block)
     * e.g. Bootstrap tooltips, or Bootstrap dropdowns, or some .js editor
     * @param array $js
     * @return void
     */
    public function setJs($js = []): void
    {
        foreach ($js as $key)
            $this->js .= <<<HEREDOC
$key\n
HEREDOC;
    }

    /**
     * anything else we want to set (in <head> block)
     * @param null $other
     * @return void
     */
    public function setOther($other = null): void
    {
        $this->other .= <<<HEREDOC
$other\n\n
HEREDOC;
    }

    /**
     * set the document title that will be shown on the browser-tab and used in search engine results
     * @param string $title defaults to 'projectname'
     * @return void
     */
    public function setTitle($title = 'projectname'): void
    {
        $this->title = <<<HEREDOC
<title>$title</title>\n\n
HEREDOC;
    }

    /**
     * set the document footer
     * @param string|null $footerContent
     * @param string|null $other
     */
    public function setFooter($footerContent = null, $other = null): void
    {
        /** set default: copyright symbol and year */
        $copyRightSymbol = " &#169 ";
        $copyrightYear = date("Y");
        $footerContent .= $copyRightSymbol . $copyrightYear; // append Copyright notice: c YYYY - to footer content
        $this->footer = <<<HEREDOC
</main>

<footer class="footer bg-dark">
    <div class="container text-center">
        <span class="text-muted" style="color: #FFFFFF !important;">$footerContent</span>
    </div>
</footer>

</body><!-- end body element, opening tag in header -->
$other
</html><!-- end html element, opening tag in header -->
HEREDOC;
    }

    /**
     * @param string|null $title
     * @param array|null $styleSheets
     * @param array|null $libs
     * @param array|null $js
     * @param string|null $other
     * @return string
     */
    public function head($title = null, $styleSheets = null, $libs = null, $js = null, $other = null): string
    {
        $invoke = (isset($styleSheets)) ? $this->setStyles($styleSheets) : '';
        $invoke = (isset($libs)) ? $this->setLibs($libs) : '';
        $invoke = (isset($js)) ? $this->setJs($js) : '';

        $head = <<<HEREDOC
$this->htmlInit

		<!-- required meta tags -->
        $this->meta

		<!-- CSS -->
        $this->styles

        <!-- Libraries - jQuery first, then Popper.js, then Bootstrap.js -->
        $this->libs
        
        <!-- jQuery / JavaScript / CSS complementary -->\n
        $this->js
            
        <!-- Other -->
        $this->other

		<title>$title</title>\n
	</head>\n
HEREDOC;
        return $head;
    }

    /*
     * getters
     */

    /**
     * @return string
     */
    public function getHtmlInit(): string
    {
        return $this->htmlInit;
    }

    /**
     * @return string
     */
    public function getMeta(): string
    {
        return $this->meta;
    }

    /**
     * @return string
     */
    public function getStyles(): string
    {
        return $this->styles;
    }

    /**
     * @return string
     */
    public function getLibs(): string
    {
        return $this->libs;
    }

    /**
     * @return string
     */
    public function getFooter(): string
    {
        return $this->footer;
    }


    /**
     * Create a Bootstrap modal
     *
     * https://getbootstrap.com/docs/4.1/components/modal/
     * Bootstrap modals are built with HTML, CSS, and JavaScript. They’re positioned over everything else in the document and remove
     * scroll from the <body> so that modal content scrolls instead.
     * Clicking on the modal “backdrop” will automatically close the modal.
     * Bootstrap only supports one modal window at a time. Nested modals aren’t supported as we believe them to be poor user
     * experiences.
     * Modals use position: fixed, which can sometimes be a bit particular about its rendering. Whenever possible, place your
     * modal HTML in a top-level position to avoid potential interference from other elements. You’ll likely run into issues
     * when nesting a .modal within another fixed element.
     * Once again, due to position: fixed, there are some caveats with using modals on mobile devices. See our browser support
     * docs for details.
     * Due to how HTML5 defines its semantics, the autofocus HTML attribute has no effect in Bootstrap modals. To achieve the
     * same effect, use some custom JavaScript:
     *
     * @param string|null $title
     * @param string|null $msg
     * @param bool $showOnload
     * @param string $id
     * @return string
     */
    public function modal($title = null, $msg = null, $showOnload = false, $id = 'yhModal'): string
    {
        $onload = <<<HEREDOC
<script type="text/javascript">
    $(window).on('load',function(){
        $('#$id').modal('show');
    });
</script>\n
HEREDOC;

        $modalOpen = <<<HEREDOC
<div class="modal fade" id="$id" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLongTitle">$title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">\n
HEREDOC;

        $modalClose = <<<HEREDOC
      \n</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
HEREDOC;
        $showOnload = ($showOnload === true) ? $onload : ''; // jQuery script to trigger the modal on page-load
        $modalHtml = $showOnload . $modalOpen . $msg . $modalClose;
        return $modalHtml;
    }

    /**
     * @param array $params
     * @return string
     */
    public function newForm(array $params = [])
    {
        // first set defaults
        $inputFields = [];
        $submitDisplay = 'submit';
        $method = 'POST';
        $action = '#';
        $formId = 'formId';
        $backHref = false;
        $confirmationDialog = false;

        /**
         * now process the user supplied parameters in $params[]
         * we use the language construct list(),
         * which has two possible notations:
         * 1. list($key => $value) = $params;
         * 2. [$key => $value] = $params;
         *
         * using $$ notation, we can create variables on the fly
         * e.g. $key == 'inputFields', $$key = $inputFields
         * effectively the user setting/overwriting default variables with a named parameter
         */
        foreach ($params as $key => $value) {
            list($key => $$key) = $params;
        }

        $actionAttrib = <<<HEREDOC
action="$action"
HEREDOC;
        $action = ($action !== false) ? $actionAttrib : "";

        $formOpen = <<<HEREDOC
        <div class="bs-docs-section">
        <div class="bs-component">
        <form id="$formId" method="$method" $action class="form-horizontal">
        <fieldset>\n
HEREDOC;
        // fields
        $formFields = null;
        $inputFields = $inputFields ?? [];
        foreach ($inputFields as $key => $value) {
            $type = $value[0] ?? "";
            $name = $value[1] ?? "";
            $id = $value[2] ?? $name; // if id is not set, then set it equal to $name
            $fieldValue = $value[3] ?? ""; // can be useful to set initial value or for hidden form fields where a field value can be carried-over to next page
            $placeholder = $value[4] ?? "";
            $options = $value[5] ?? null; // $value[5] contains an array with options(for e.g. to set 'required' or for select or radio buttons)
            $label = ($type !== 'hidden') ? $name : "";

            /** type: text or password or email */
            if ($type === 'text' || $type === 'password' || $type === 'email' || $type === 'hidden') {
                $formFields .= <<<HEREDOC
        <div class="form-group">
            <label for="$id" class="col-sm-2 control-label">$label</label>
            <div class="col-sm-10">
            <input type="$type" class="form-control" name="$name" id="$id" value="$fieldValue" placeholder="$placeholder" $options[0]>
            </div>
        </div>\n
HEREDOC;
            }

            /** type: select */
            if ($type === 'select') {
                $formFields .= <<<HEREDOC
        <div class="form-group">
            <label for="$name" class="col-sm-2 control-label">$name</label>
            <div class="col-sm-10">
            <select class="form-control" id="$id" name="$name">\n
HEREDOC;
                foreach ($options as $key) {
                    $formFields .= <<<HEREDOC
                <option>$key</option>\n
HEREDOC;
                }

                $formFields .= <<<HEREDOC
            </select>
            </div>
        </div>\n
HEREDOC;
            }

            /** type: radio */
            if ($type === 'radio') {
                $formFields .= <<<HEREDOC
        <fieldset class="form-group">
HEREDOC;
                foreach ($options as $key => $value) {
                    $formFields .= <<<HEREDOC
        <div class="form-check">
            <label class="form-check-label">
            <div class="col-sm-10">
                <input type="radio" class="form-check-input" name="$name" id="$id" value="$key" $value>
                $key
            </div> 
            </label>
        </div>\n
HEREDOC;
                }
                $formFields .= <<<HEREDOC
        </fieldset>
HEREDOC;
            }
        }

        if ($backHref) {
            $backButton = <<<HEREDOC
        <button type="button" class="btn btn-primary pull-right" onclick="location.href='$backHref';">Back</button>\n
HEREDOC;
        } else {
            $backButton = '';
        }

        /**
         *                          SUBMIT or SUBMIT && CONFIRMATION button
         *
         * a normal submit button, or a confirmation button with dialog (e.g. are you sure? 'confirm', 'cancel')
         * we thus define: '$normalSubmit' and '$confirmSubmit' html and store the appropriate html in '$submitButton'
         */

        $normalSubmit = <<<HEREDOC
        <button type="submit" name="submit" class="btn btn-primary">$submitDisplay</button>
HEREDOC;

        $confirmSubmit = ($confirmationDialog === true) ? $this->confirmationDialog($submitDisplay, 'confirmationDialog', 'Please confirm...', false) : false;

        /**
         *  store either 'normal' or 'confirmation' button in $submitButton
         *
         * IMPORTANT: the normal button has field-name 'submit', whereas the 'confirmation' button field-name is 'confirm'
         *              so when checking e.g. $_POST, make sure you're checking the correct field-name.
         */
        $submitButton = ($confirmSubmit !== false) ? $confirmSubmit : $normalSubmit;

        // close form
        $formClose = <<<HEREDOC
        <div class="form-group">
            <div class="col-sm-offset-2 p-3 col-sm-10">
            $submitButton
            $backButton
            </div>
        </div>

        </fieldset>
        </form>
        </div>
        </div>\n\n
HEREDOC;

        // close form
        $formCloseNoSubmit = <<<HEREDOC
        </fieldset>
        </form>
        </div>
        </div>\n\n
HEREDOC;

        // set  $submitDisplay to false to render a form without a 'submit' button
        $formHtmlSubmit = $formOpen . $formFields . $formClose; // normal case, most often a submit button will be needed
        $formHtmlNoSubmit = $formOpen . $formFields . $formCloseNoSubmit; // case where no submit button is needed (e.g. when we substitute it with a confirmationDialog() to be sure a delete action was requested)
        $formHtml = ($submitDisplay !== false) ? $formHtmlSubmit : $formHtmlNoSubmit;

        return $formHtml;
    }


    /**
     * Form
     *
     * @param string $submitDisplay set it to false if no form submit button should be rendered, anything else will be displayed on button
     *                                  a typical use-case for a form without a submit button is where we substitute it for a confirmDialog button (are you sure...)
     *                                  where field-name 'submit' becomes 'confirm' (e.g. delete actions, where we need to be sure this is what user wants).
     *
     * @param array inputFields         array that holds an array for each input-field: ['type', 'name', 'id', 'value', 'placeholder', options[]]
     *
     *                                  'type' in   case formfield:     set 'type' to - 'text' or 'email' or 'password', or...
     *
     *                                      case selectbox:     set 'type' to - 'select' - select type html-block
     *
     *                                      case radiobuttons:  set 'type' to - 'radio'
     *
     *                                      case checkboxes:    set 'type' to - 'checkbox'
     *
     *                                      case file:          set 'type' to - 'file'
     *
     *                                      'name' is the reference to retrieve the user-input in the $_POST(default) or $_GET array
     *
     *                                      'id' is the identifier, can be used for e.g. javascript or CSS reference
     *
     *                                      'value' is the initial value that can be set in the input box (can be handy in setting 'hidden' input box values).
     *
     *                                      'placeholder' shows in the input field as a 'hint'
     *
     *                                      options[] - e.g. set 'required' on a field, or define select-list-items, or set checked on a default choice radio button.
     *
     * EXAMPLE:
     * $inputFields =  [
     *                  ['text', 'slug', 'slug', "", 'enter article slug'],
     *                  ['hidden', 'existingArticleId', 'existingArticleId', $existingArticleId, ""],
     *                  ['hidden', 'store', 'store', $store, ""] // a same-type form-field needs to be 'marked' with an '*'
     *                 ];
     * $form = (new libs\BootWrap())->form($inputFields, false); // 'false' as we do not need a submit button, we have a seperate button to trigger jQuery code
     *
     * @backHref    a 'back' button, just set the href for the target location.
     */

    /**
     * @param array $inputFields text, password, select, hidden...
     * @param string $submitDisplay
     * @param string $method
     * @param string $action
     * @param string $formId
     * @param bool $backHref
     * @param array $confirmationDialog [0] set to true if you want a confirmation dialog, [1] set to true if you want href text, true if you want a button.
     * @return string
     */
    public function form($inputFields = [], $submitDisplay = 'submit', $method = 'POST', $action = "#", $formId = "formId", $backHref = false, $confirmationDialog = [false, true])
    {
        /**
         * we use a short HEREDOC to define the action attribute
         * because when $action === false, we want no action attribute at all in $formOpen
         * this is because, until HTML5, the action attribute was required. This is no longer needed.
         * This way we are certain the system uses Ajax to send data to server (if that's what we want)
         * and doesn't refresh the page (when going to/requesting id '#' on the same page').
         */
        $actionAttrib = <<<HEREDOC
action="$action"
HEREDOC;
        $action = ($action !== false) ? $actionAttrib : "";

        $formOpen = <<<HEREDOC
        <div class="bs-docs-section">
        <div class="bs-component">
        <form id="$formId" method="$method" $action class="form-horizontal">
        <fieldset>\n
HEREDOC;

        $formFields = null;

        /**
         * $inputFields[]
         *   [0]      [1]    [2]    [3]         [4]         [5]       [6]
         * ['type', 'name', 'id', 'value', 'placeholder', 'label', options[]]   /** added 'label' on 2018/07/03 */
        $inputFields = $inputFields ?? [];
        foreach ($inputFields as $key => $value) {
            $type = $value[0] ?? "";
            $name = $value[1] ?? "";
            $id = $value[2] ?? $name; // if id is not set, then set it equal to $name
            $fieldValue = $value[3] ?? ""; // can be useful to set initial value or for hidden form fields where a field value can be carried-over to next page
            $placeholder = $value[4] ?? "";
            $label = ($type !== 'hidden') ? $value[5] : "";
            $options = $value[6] ?? null; // $value[6] contains an array with options(for e.g. to set 'required' or for select or radio buttons)


            /** type: text or password or email */
            if ($type === 'text' || $type === 'password' || $type === 'email' || $type === 'hidden') {
                $formFields .= <<<HEREDOC
        <div class="form-group">
            <label for="$id" class="col-sm-2 control-label">$label</label>
            <div class="col-sm-10">
            <input type="$type" class="form-control" name="$name" id="$id" value="$fieldValue" placeholder="$placeholder" $options[0]>
            </div>
        </div>\n
HEREDOC;
            }

            /** type: select */
            if ($type === 'select') {
                $formFields .= <<<HEREDOC
        <div class="form-group">
            <label for="$id" class="col-sm-2 control-label">$label</label>
            <div class="col-sm-10">
            <select class="form-control" id="$id" name="$name">\n
HEREDOC;
                foreach ($options as $key) {
                    $formFields .= <<<HEREDOC
                <option>$key</option>\n
HEREDOC;
                }

                $formFields .= <<<HEREDOC
            </select>
            </div>
        </div>\n
HEREDOC;
            }

            /** type: radio */
            if ($type === 'radio') {
                $formFields .= <<<HEREDOC
        <fieldset class="form-group">
HEREDOC;
                foreach ($options as $key => $value) {
                    $formFields .= <<<HEREDOC
        <div class="form-check">
            <label class="form-check-label">
            <div class="col-sm-10">
                <input type="radio" class="form-check-input" name="$name" id="$id" value="$key" $value>
                $key
            </div> 
            </label>
        </div>\n
HEREDOC;
                }
                $formFields .= <<<HEREDOC
        </fieldset>
HEREDOC;
            }
        }

        if ($backHref) {
            $backButton = <<<HEREDOC
        <button type="button" class="btn btn-primary pull-right" onclick="location.href='$backHref';">Back</button>\n
HEREDOC;
        } else {
            $backButton = '';
        }

        /**
         * a normal submit button, or a confirmation button with dialog (e.g. are you sure? 'confirm', 'cancel')
         * we thus define: '$normalSubmit' and '$confirmSubmit' html and store the appropriate html in '$submitButton'
         */

        $normalSubmit = <<<HEREDOC
        <button type="submit" name="submit" class="btn btn-primary">$submitDisplay</button>
HEREDOC;
        // confirmationDialog($display = '', $id = 'confirmationDialog', $uniqueConfirmName = 'confirm', $msg = 'Please confirm...', $href = [])
        // $confirmationDialog
        $href = ($confirmationDialog[1] === true) ? true : false; // a button (if false), or a href text (if true)
        $confirmSubmit = ($confirmationDialog[0] === true) ? $this->confirmationDialog($submitDisplay, $id = 'confirmationDialog', $uniqueConfirmName = 'confirm', $msg = 'Please confirm...', $href) : false;

        /**
         *  store either 'normal' or 'confirmation' button in $submitButton
         *
         * IMPORTANT: the normal button has field-name 'submit', whereas the 'confirmation' button field-name is 'confirm'
         *              so when checking e.g. $_POST, make sure you're checking the correct field-name.
         */
        $submitButton = ($confirmSubmit !== false) ? $confirmSubmit : $normalSubmit;

        // close form
        $formClose = <<<HEREDOC
        <div class="form-group">
            <div class="col-sm-offset-2 p-3 col-sm-10">
            $submitButton
            $backButton
            </div>
        </div>

        </fieldset>
        </form>
        </div>
        </div>\n\n
HEREDOC;

        // close form
        $formCloseNoSubmit = <<<HEREDOC
        </fieldset>
        </form>
        </div>
        </div>\n\n
HEREDOC;

        // set  $submitDisplay to false to render a form without a 'submit' button
        $formHtmlSubmit = $formOpen . $formFields . $formClose; // normal case, most often a submit button will be needed
        $formHtmlNoSubmit = $formOpen . $formFields . $formCloseNoSubmit; // case where no submit button is needed (e.g. when we substitute it with a confirmationDialog() to be sure a delete action was requested)
        $formHtml = ($submitDisplay !== false) ? $formHtmlSubmit : $formHtmlNoSubmit;

        return $formHtml;
    }

    public function searchForm($method = 'POST', $name = 'search', $action = false)
    {
        /**
         * we use a short HEREDOC to define the action attribute
         * because when $action === false, we want no action attribute at all in $formOpen
         * this is because, until HTML5, the action attribute was required. This is no longer needed.
         * This way we are certain the system uses Ajax to send data to server (if that's what we want)
         * and doesn't refresh the page (when going to/requesting id '#' on the same page').
         */
        $actionAttrib = <<<HEREDOC
action="$action"
HEREDOC;
        $action = ($action !== false) ? $actionAttrib : "";

        $formId = $name . date("His"); //construct a unique id by appending timestamp: e.g. 221759 (22 = hours, 17=mins, 59=secs)

        $searchFormHtml = <<<HEREDOC
    <div class='row'>
    <div class='col'>
        <form id="$formId" method="$method" $action class="form-inline pull-right my-2 my-lg-0">
            <input class="form-control mr-sm-2" name="$name" type="search" placeholder="Search" aria-label="Search">     
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
    </div>\n
HEREDOC;
        return $searchFormHtml;
    }

    /**
     * dropdown items only work with js-plugin - we include the functionality in header.php
     * <script>
     *  $(function () {
     *       $('.dropdown-toggle').dropdown()
     *    });
     *   </script>
     *
     * @param string $menuDisplay
     * @param array $navItems ['display1'=>'href1', 'display2'=>'href2' etc...]
     *
     *                                  if you want to insert a divider (horizontal ruler)
     *                                  just insert ''=>''
     *                                  e.g.
     *                                  ['display1'=>'href1', ''=>'', 'display2'=>'href2'] renders a divider between the two anchors
     * @param string $activeNav 'nav-item' or 'nav-item-active'
     */
    public function dropDown($menuDisplay = 'dropdown', $navItems = [], $activeNav = null, $class = 'primary', $size = 'md')
    {
        $dropDownItems = null;
        /*
         * we encountered a problem highlighting the drop-down menu button ($menuDisplay) on selection
         * e.g. when user clicks drop-down button 'Dashboard', trying to highlight it with class 'active'
         * or 'nav-item active' doesn't work, so we set it manually here.
         */
        //$textNormalColor = "color: #FFFFFF;"; // not selected, we set it to grey-blue
        //$textActiveColor = "color: #FFC000;"; // selected, highlight text
        $textNormalColor = "color: " . config\Config::TXTCOLOR_NORMAL_NAV;
        $textActiveColor = "color: " . config\Config::TXTCOLOR_ACTIVE_NAV;
        $color = ($activeNav === 'nav-item active') ? $textActiveColor : $textNormalColor;

        $dropDownOpen = <<<HEREDOC
        <div class="btn-group" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-$class btn-$size m-3 $activeNav dropdown-toggle" style='$color' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        $menuDisplay
        </button>
           <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
HEREDOC;

        foreach ($navItems as $key => $value) {
            $display = $key;
            $href = $value;
            $divider = false;

            /** see if a divider was requested (i.e. client set a key starting with 'hr', e.g.: 'hr1', 'hr2' etc. */
            if (\substr($key, 0, 2) === 'hr' && $value === '') {
                $href = '';
                $display = '';
                $divider = true;
            }

            $anchor = <<<HEREDOC
            <a class="dropdown-item" href="$href">$display</a>\n
HEREDOC;
            $dividerHr = <<<HEREDOC
            <div class="dropdown-divider"></div>   
HEREDOC;

            if ($divider === true) {
                $dropDownItems .= $dividerHr;
            } else {
                $dropDownItems .= $anchor;
            }
        }

        $dropDownClose = <<<HEREDOC
          </div>
        </div>
HEREDOC;

        $dropDownHtml = $dropDownOpen . $dropDownItems . $dropDownClose;
        return $dropDownHtml;
    }

    /**                                 NAVIGATION BUTTONS
     *
     * Button used for navigation - these are not the same as actual <button ...></button> buttons.
     * <button ...></button> are action buttons (e.g. submit button in a form), and normally not used for navigation.
     *
     * We do however offer a type <button></button> option here as a navigation button,
     * because we like the styling of these buttons, the outline style.
     *
     * To invoke this type of button as a navButton, the client sets the $type argument to 'button'.
     *
     * @param string $href link's destination
     * @param string $activeNav set to a nav-item display value: e.g. 'home' to set the active button (highlighted) for the 'home' page-view
     * @param string $display text displayed on button
     * @param string $class primary, secondary, success, danger, warning, info, light, dark
     * @param string $size 'lg' for large, 'sm' for small
     * @param string $type 'button' for a button-type nav-button
     */
    public function navButton($display = 'click me', $href = '#', $activeNav = null, $type = null, $class = 'primary', $size = null)
    {
        $textNormalColor = "color: " . config\Config::TXTCOLOR_NORMAL_NAV;
        $textActiveColor = "color: " . config\Config::TXTCOLOR_ACTIVE_NAV;
        $color = ($activeNav === 'nav-item active') ? $textActiveColor : $textNormalColor;

        $navButton1 = <<<HEREDOC
        <a href="$href" class="btn btn-$class btn-$size m-3 nav-link pull-left $activeNav" style='$color'>$display</a>\n
HEREDOC;

        $navButton2 = <<<HEREDOC
        <button type="button" class="btn btn-outline-$class btn-$size m-3 nav-link pull-left $activeNav" style='$color' onclick="location.href='$href';">$display</button>\n
HEREDOC;

        $navButtonHtml = ($type !== 'button') ? $navButton1 : $navButton2;
        return $navButtonHtml;
    }

    /**                                 ACTION BUTTONS
     *
     * Button used in anything but navigation, e.g. forms, dialogs
     * The client can invoke the rendering of a 'filled'(default) or 'outline' button
     *
     * we can also turn the action buttons into navigation buttons, like so:
     * <button type="button" class="btn btn-outline-primary" onclick="location.href='$url';">navigational action</button>
     *
     * we offer this functionality in BootWrap::navButton()
     *
     * @param string $display text displayed on button
     * @param string $class primary, secondary, success, danger, warning, info, light, dark
     * @param string $outline default solid fill, set to true if you want an outline-styled button
     */
    public function actButton($display = 'click me', $class = 'primary', $outline = false)
    {
        $button = <<<HEREDOC
        <button type="button" class="btn btn-$class">$display</button>\n
HEREDOC;

        $buttonOl = <<<HEREDOC
        <button type="button" class="btn btn-outline-$class">$display</button>\n
HEREDOC;

        // does the user want a filled / outline button
        $actButtonHtml = ($outline !== true) ? $button : $buttonOl;
        return $actButtonHtml;
    }

    public function href($link = null, $display = 'click me', $class = 'primary', $cssClass = null)
    {
        $hrefHtml = <<<HEREDOC
       <ul class="$cssClass" style='margin-left: 10px;'><a href="$link">$display</a></ul>
HEREDOC;
        return $hrefHtml;
    }
    /**
     * start NEW
     */

    /**
     * Bootstrap component          nav
     *
     * BootWrap::navBarNavButtons() custom-made(YH) navigation bar with navButtons
     *
     * @param array $navItems []     set each nav-item's display-name (key) and href (value)
     *
     *                                    display  href    display     href
     *                              e.g. ['home'=>'index', 'contact'=>'contact']
     *
     * @param string $type 'button' for a button-type nav-button
     * @param string $class primary, secondary, success, danger, warning, info, light, dark
     * @param string $size 'lg' for large, 'sm' for small, md for medium
     *
     * @param alignment             place the navbar at 'top', 'bottom', or make it 'sticky' (scroll with content)
     * @param logo                  logo[0]=display text, logo[1]=href to image
     *
     *
     * EXAMPLE: set appropriate nav-items dependent on the user profile: authorised / not authorised
     * $nonAuthUser = ['home'=>'index', 'login'=>'login', 'contact us'=>'contact'];
     * $authUser = ['home'=>'index', 'quill'=>'quill', 'repo'=>'repo', 'contact us'=>'contact', 'register new user'=>'dashboard/register', 'logout'=>'logout'];
     * $navItems = ($logged === 'true') ? $authUser : $nonAuthUser;
     * $logo = ['Yellow Heroes', $logoImage];
     * $navBar = $bootWrap->navBarNavButtons($navItems, null, 'primary', 'sm', 'dark', 'dark', 'top', $logo);
     */
    public function navBarNavButtons($navItems = [], $type = null, $class = 'primary', $size = 'md', $textColor = 'dark', $bgColor = 'dark', $alignment = 'top', $logo = [], $userName = 'visitor', $toolTip = null)
    {
        $navBarNavButtons = '';

        foreach ($navItems as $key => $value) {
            $display = $key;
            $href = $value;

            $navBarNavButtons .= $this->navButton($display, $href, $type, $class, $size);
        }

        if ($alignment !== 'top') {
            if ($alignment === 'bottom') {
                $alignment = 'fixed-bottom';
            } elseif ($alignment === 'sticky') {
                $alignment = 'sticky-top';
            }
        } else {
            $alignment = 'fixed-top';
        }

        $navBarNavButtonsHtml = <<<HEREDOC
<nav class="navbar $alignment navbar-expand-lg navbar-$textColor p-3 bg-$bgColor">
    <img src="{$logo[1]}" width="24" height="24"><a class="navbar-brand p-2" href="">{$logo[0]}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
 <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
$navBarNavButtons
    </ul>   
 </div>
 <p class="navbar-text text-warning bg-dark navbar-right m-3" data-toggle="tooltip" data-placement="auto" title="$toolTip">User Name: $userName</p>
</nav>\n

HEREDOC;
        return $navBarNavButtonsHtml;
    }
    /**
     * end NEW
     */
    /**
     * NEW navBarEnhanced
     * with navItems['type'=>['display', 'href'], 'type'=>['display', 'href']
     * because we want to be able to add multiple types (button, drop-down, ...)
     *
     */

    /**
     * Bootstrap component          nav
     *
     * BootWrap::navBarNavButtons() custom-made(YH) navigation bar with navButtons
     *
     * @param array $navItems []     set each nav-item's display-name (key) and href (value)
     *
     *                                    display  href    display     href
     *                              e.g. ['home'=>'index', 'contact'=>'contact']
     *
     *                              and if you want to add a dropdown menu, enter a [] with the dropdown menu items as follows:
     *
     *                                    display  href    display     href             menu-display         display            href            display             href
     *                              e.g. ['home'=>'index', 'contact'=>'contact', 'dropdownMenuDisplay'=>['dropdownitem1'=>'dropdownhref1', 'dropdowndisplay2'=>'dropdownhref2', etc...]]
     *
     *                              the function will recognise the dropdown as the $value === type array. This triggers the function to call Bootwrap::dropdown().
     *
     * @param string $activeNav set to a nav-item display value: e.g. 'home' to set the active button (highlighted) for the 'home' page-view
     * @param string $class primary, secondary, success, danger, warning, info, light, dark
     * @param string $size 'lg' for large, 'sm' for small, md for medium
     *
     * @param alignment             place the navbar at 'top', 'bottom', or make it 'sticky' (scroll with content)
     * @param logo                  logo[0]=display text, logo[1]=href to image
     *
     *
     * EXAMPLE: set appropriate nav-items dependent on the user profile: authorised / not authorised
     * $nonAuthUser = ['home'=>'index', 'login'=>'login', 'contact us'=>'contact'];
     * $authUser = ['home'=>'index', 'quill'=>'quill', 'repo'=>'repo', 'contact us'=>'contact', 'register new user'=>'dashboard/register', 'logout'=>'logout'];
     * $navItems = ($logged === 'true') ? $authUser : $nonAuthUser;
     * $logo = ['Yellow Heroes', $logoImage];
     * $navBar = $bootWrap->navBarNavButtons($navItems, null, 'primary', 'sm', 'dark', 'dark', 'top', $logo);
     */
    public function navBarEnhanced($navItems = [], $activeNav = null, $type = null, $class = 'primary', $size = 'md', $textColor = 'dark', $bgColor = 'dark', $alignment = 'top', $logo = [], $userName = 'visitor', $toolTip = null, $location = null, $search = false)
    {
        $buttons = '';

        foreach ($navItems as $key => $value) {
            $display = $key;
            $href = $value;
            $active = ($activeNav === $display) ? 'nav-item active' : 'nav-item';

            if (is_array($value)) {
                $buttons .= $this->dropDown($key, $value, $active, $class, $size); // $key=display text on menu button, $value==[] with nav-items ['display1'=>'href1', 'display2'=>'href2', etc...]
            } else {
                $buttons .= $this->navButton($display, $href, $active, $type, $class, $size);
            }
        }

        if ($alignment !== 'top') {
            if ($alignment === 'bottom') {
                $alignment = 'fixed-bottom';
            } elseif ($alignment === 'sticky') {
                $alignment = 'sticky-top';
            }
        } else {
            $alignment = 'fixed-top';
        }

        $searchFormHtml = <<<HEREDOC
    <form class="form-inline my-2 my-lg-0" method="post">
      <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">     
      <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button> -->
    </form>\n
HEREDOC;
        $searchFormHtml = ($search !== false) ? $searchFormHtml : ''; // include search form html or keep empty if $search === false

        $navBarEnhancedHtml = <<<HEREDOC
<nav class="navbar $alignment navbar-expand-lg navbar-$textColor p-3 bg-$bgColor">
    <img src="{$logo[1]}" width="24" height="24"><a class="navbar-brand p-2" href="{$logo[2]}">{$logo[0]}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
 <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="nav navbar-nav">
$buttons
    </ul>
 $searchFormHtml
    <div class='col pull-right'>
    <p class="navbar-text text-right pull-right text-white bg-dark navbar-right m-3" data-toggle="tooltip" data-placement="auto" title="{$toolTip[0]}"><i class="fa fa-user-circle-o"></i> $userName </p>
    <p class="navbar-text text-right pull-right text-white bg-dark navbar-right m-3" data-toggle="tooltip" data-placement="auto" title="{$toolTip[1]}"><i class="fa fa-map-marker"></i> $location</p>  
    </div>
   </div>
</nav>\n
HEREDOC;

        return $navBarEnhancedHtml;
    }
    /**
     * end NEW navBarEnhanced
     */

    /**                     NEEDS LOOK, DOESNT FUNCTION
     *
     *
     *
     * Bootstrap component          nav
     *
     * BootWrap::navBar()           basic navigation bar with buttons and dropdown(s)
     *
     * @param array $navItems []     set each nav-item's display-name (key) and href=viewsDir (value)
     *
     *                                    display  href    display     href
     *                              e.g. ['home'=>'/index', 'contact'=>'/contact']
     *
     * @param textColor             dark or light
     *
     * @param bgColor               primary, secondary, success, danger, warning, info, light, dark
     *
     * @param placement             top, bottom, sticky
     *
     * @param logo                  href to a small logo that will be rendered as first item on nav-bar
     *
     * @param string $alignment default the navbar renders from the left
     *                              options:    center
     *                                          right
     *                                          fill (extend the full available width)
     *                                          stack  (stacked tabs)
     */
    public function navBar($navItems = [], $textColor = null, $bgColor = null, $alignment = 'top', $logo = null)
    {
        $anchors = '';
        $divs = '';
        $count = 0;
        foreach ($navItems as $key => $value) {
            //$active = ($count === 0) ? 'active' : ''; // 'active' is CSS code, only first <a></a> needs this
            $active = '';
            //$sronly = ($count === 0) ? '<span class="sr-only">(current)</span>' : '';
            $sronly = '';
            $display = $key;
            $href = $value;

            // first, generate an anchor for each 'nav-item'
            $anchor = <<<HEREDOC
<li><a href="$href">$display</a></li>\r\n
HEREDOC;
            $anchors .= $anchor;

            $count++;
        }

        if ($alignment !== 'top') {
            if ($alignment === 'bottom') {
                $alignment = 'fixed-bottom';
            } elseif ($alignment === 'sticky') {
                $alignment = 'sticky-top';
            }
        } else {
            $alignment = 'fixed-top';
        }

        $navBar = <<<HEREDOC
<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <div class="nav-collapse collapse">
            <ul class="nav">
$anchors
    </ul>
          </div><!--/.nav-collapse -->
       </div>
      </div>
    </div>\n

HEREDOC;
        $navBarHtml = $navBar;
        return $navBarHtml;
    }

    /**
     * Bootstrap component          nav
     *
     * BootWrap::navTabs()          navigate using tabs - content loaded with jQuery Ajax
     *
     * @param array $tabs set element '#id' and tab display-name for each 'tab'
     *
     *                                     DOM      tab      DOM          tab
     *                                      id    display     id        display
     *                              e.g. ['home'=>'home', 'contact'=>'contact us']
     *
     * @param array $pageContent the content that will be rendered on each 'tab' (page)
     *                              argument can be a simple string or a file (e.g. index.php, contact.html, something.txt...)
     *                              e.g. $pageContent = ['Site Under Construction', 'Something about the team...', 'index3.php'];
     *                              The first argument is content that will be rendered on the first 'tab', the second arg. is content for the second 'tab', etc.
     *
     * @param string $alignment default the tabs render from the left
     *                              options:    center
     *                                          right
     *                                          fill (extend the full available width)
     *                                          stack  (stacked tabs)
     */
    public function navTabs($tabs = [], $pageContent = [], $alignment = null)
    {
        $anchors = '';
        $divs = '';
        $count = 0;
        foreach ($tabs as $key => $value) {
            $active = ($count === 0) ? 'active' : ''; // 'active' is CSS code, only first <a></a> needs this
            $id = $key;
            $displayName = $value;

            // first, generate an anchor for each 'page'
            $anchor = <<<HEREDOC
<a class="nav-item nav-link $active" id="nav-$id-tab" data-toggle="tab" href="#nav-$id" role="tab" aria-controls="nav-$id" aria-selected="true">$displayName</a>\r\n
HEREDOC;
            $anchors .= $anchor;

            // second, fetch content for each 'page' / 'tab'
            // page content can be hardcoded as a string, or, more likely, be a file (.php, .html, .txt...)
            // we put the string-content / filename in $content
            $active = ($count === 0) ? 'show active' : ''; // 'show active' is CSS code, only first <div> needs this
            $content = $pageContent[$count];

            // use jQuery ajax to retrieve the $content from file for output on the 'page' / 'tab'
            $loadAjax = <<<HEREDOC

<script>
$(function() {
    $("#nav-$id").load("$content", function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
            console.log("page content loaded successfully!");
        if(statusTxt == "error")
            console.log("Error: " + xhr.status + ": " + xhr.statusText);
    });
});
</script>\n
HEREDOC;

            $insertContent = (is_file($content)) ? $loadAjax : $content;
            $div = <<<HEREDOC
<div class="tab-pane fade $active" id="nav-$id" role="tabpanel" aria-labelledby="nav-$id-tab">$insertContent</div>\n
HEREDOC;
            $divs .= $div;
            $count++;
        }

        if ($alignment !== null) {
            if ($alignment === 'center') {
                $alignment = 'justify-content-center';
            } elseif ($alignment === 'right') {
                $alignment = 'justify-content-end';
            } elseif ($alignment === 'fill') {
                $alignment = 'nav-fill';
            } elseif ($alignment === 'stack') {
                $alignment = 'flex-column';
            }
        } else {
            $alignment = '';
        }

        $navTabs = <<<HEREDOC

<nav>
<div class="nav $alignment nav-tabs" id="nav-tab" role="tablist">
$anchors
</div>
</nav>\n

HEREDOC;

        $tabContent = <<<HEREDOC
<div class="tab-content" id="nav-tabContent">
$divs
</div>

HEREDOC;
        $navTabHtml = $navTabs . $tabContent;
        return $navTabHtml;
    }

    /**
     * Bootstrap component:         nav
     *
     * BootWrap::navPills()         navigate using pills(pill-shaped buttons) - content loaded with jQuery Ajax
     *
     * @param array $pills set element '#id' and tab display-name for each 'tab'
     *
     *                                     DOM      tab      DOM          tab
     *                                      id    display     id        display
     *                              e.g. ['home'=>'home', 'contact'=>'contact us']
     *
     * @param array $pageContent the content that will be rendered on each 'tab' (page)
     *                              argument can be a simple string or a file (e.g. index.php, contact.html, something.txt...)
     *                              e.g. $pageContent = ['Site Under Construction', 'Something about the team...', 'index3.php'];
     *                              The first argument is content that will be rendered on the first 'tab', the second arg. is content for the second 'tab', etc.
     *
     * @param string $alignment default the pills render from the left
     *                              options:    'center'
     *                                          'right'
     *                                          'fill' (extend the full available width)
     *                                          'stack'  (stacked pills - i.e. a vertical navigation menu)
     *                                          (if 'stack' is selected, client can set the width of the buttons with $grid)
     *
     * @param int $grid for vertically stacked pills, set relative width for pills (# columns out of total 12)
     *                              default 3 columns for pills, and remaining 9 columns for the content
     *
     */
    public function navPills($pills = [], $pageContent = [], $alignment = null, $grid = 3)
    {
        $orientation = '';
        $addMarkup1 = '';
        $addMarkup2 = '';
        $addMarkup3 = '';
        $addMarkup4 = '';
        $anchors = '';
        $divs = '';
        $count = 0;
        foreach ($pills as $key => $value) {
            $active = ($count === 0) ? 'active' : ''; // 'active' is CSS code, only first <a></a> needs this
            $boolean = ($count === 0) ? 'true' : 'false'; // 'boolean' is CSS code, only first <a></a> needs this
            $id = $key;
            $displayName = $value;
            $v = ($alignment === 'stack') ? 'v-' : ''; // prepend all id's with 'v-' in case of vertical pills
            // first, generate an anchor for each 'page'
            $anchor = <<<HEREDOC
<a class="nav-link $active" id="{$v}pills-$id-tab" data-toggle="pill" href="#{$v}pills-$id" role="tab" aria-controls="{$v}pills-$id" aria-selected="$boolean">$displayName</a>\r\n
HEREDOC;
            $anchors .= $anchor;

            // second, fetch content for each 'page' / 'tab'
            // page content can be hardcoded as a string, or, more likely, be a file (.php, .html, .txt...)
            // we put the string-content / filename in $content
            $active = ($count === 0) ? 'show active' : ''; // 'show active' is CSS code, only first <div> needs this
            $content = $pageContent[$count];

            // use jQuery ajax to retrieve the $content from file for output on the 'page' / 'tab'
            $loadAjax = <<<HEREDOC

<script>
$(function() {
    $("#{$v}pills-$id").load("$content", function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
            console.log("page content loaded successfully!");
        if(statusTxt == "error")
            console.log("Error: " + xhr.status + ": " + xhr.statusText);
    });
});
</script>\n
HEREDOC;

            $insertContent = (is_file($content)) ? $loadAjax : $content;
            $div = <<<HEREDOC
<div class="tab-pane fade $active" id="{$v}pills-$id" role="tabpanel" aria-labelledby="{$v}pills-$id-tab">$insertContent</div>\r\n
HEREDOC;
            $divs .= $div;
            $count++;
        }

        if ($alignment !== null) {
            if ($alignment === 'center') {
                $alignment = 'justify-content-center';
            } elseif ($alignment === 'right') {
                $alignment = 'justify-content-end';
            } elseif ($alignment === 'fill') {
                $alignment = 'nav-fill';
            } elseif ($alignment === 'stack') {
                $alignment = 'flex-column';
                $orientation = 'vertical';
                $v = 'v-';
                $gridContent = 12 - $grid; // the space left for the content (default 9 columns)
                // complementary mark-up for vertical('stack') pills
                $addMarkup1 = <<<HEREDOC
<div id='yellowheroes'>
<div class="row">
<div class="col-$grid">
HEREDOC;
                $addMarkup2 = <<<HEREDOC
</div> <!-- col-grid -->
HEREDOC;
                $addMarkup3 = <<<HEREDOC
<div class="col-$gridContent">
HEREDOC;
                $addMarkup4 = <<<HEREDOC
</div> <!-- col-gridContent -->
</div> <!-- row -->
</div> <!-- yellowheroes -->

HEREDOC;
            }
        } else {
            $alignment = '';
        }

        $navPills = <<<HEREDOC

$addMarkup1
<div class="nav $alignment nav-pills" id="{$v}pills-tab" role="tablist" aria-orientation="$orientation">
$anchors
</div> <!-- nav flex-column nav-pills -->
$addMarkup2

HEREDOC;

        $pillContent = <<<HEREDOC
$addMarkup3
<div class="tab-content" id="{$v}pills-tabContent">
$divs
</div> <!-- tab content -->
$addMarkup4
HEREDOC;

        $navPillsHtml = $navPills . $pillContent;
        return $navPillsHtml;
        //echo $navPills;
        //echo $pillContent;
    }

    /**
     * confirmationDialog() is used for situations like 'do you really want to delete blog archive ... ?'
     * confirmationDialog() works for situations where you need to set a <a href> element
     * SET $href = false if you want a button that triggers the confirmationDialog
     *
     * @param type $href = true         set to false if you want to render a button, not <a > hypertext link
     *
     * $display = the text of the href link
     * $id = the id of the element - IMPORTANT when e.g. rendering a list of blog-articles need to set individual #id's for each <div> in the list so the dialog renders in the right place
     * $msg = the confirmation message
     *
     * two field 'name' s : 'confirm' and 'cancel'
     */
    public function confirmationDialog($display = '', $id = 'confirmationDialog', $uniqueConfirmName = 'confirm', $msg = 'Please confirm...', $href = true)
    {
        $button = '';
        if ($href === true) {
            $href = <<<HEREDOC
        <a data-toggle="collapse" href="#$id" role="button" aria-expanded="false" aria-controls="collapseExample">
        $display
        </a>
HEREDOC;
        } else {
            $href = '';
            $button = <<<HEREDOC
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#$id" aria-expanded="false" aria-controls="collapseExample">
            $display
            </button>
HEREDOC;
        }

        $action = ($href !== '') ? $href : $button;


        /*
         * note: we declare a unique name for the confirm field, as we may have multiple confirmation forms on one page.
         * We can then capture a click event on $_POST['someUniqueConfirmName'], which differs from a click on $_POST['someOtherUniqueConfirmName'].
         */
        $confirmationDialogHtml = <<<HEREDOC
<p>
  $action
</p>
<div class="collapse" id="$id">
  <div class="card card-body">    
    <p>$msg</p>
    <div>
      <form method='post' action='$id'>
        <input type='submit' class="btn btn-primary" name='$uniqueConfirmName' value='confirm'>
        <input type='submit' class="btn btn-primary" name='cancel' value='cancel'>
      </form>
    </div>
	</div>
</div>
HEREDOC;

        return $confirmationDialogHtml;
    }

    /**
     * coverNav are underlined navigation items
     * the css styling sits in 'cover.css'
     */
    public function coverNav($navItems = [], $viewsDir = null, $brand = null)
    {
        $active = null;
        $anchors = null;
        foreach ($navItems as $key => $value) {
            $display = $key;
            $href = $value;
            $active = ($href === $viewsDir) ? 'active' : '';
            // generate an anchor for each 'nav-item'
            $anchor = <<<HEREDOC
<a class = "nav-link $active"  href="$href">$display</a>\r\n
HEREDOC;
            $anchors .= $anchor;
        }

        $coverNavHead = <<<HEREDOC
<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
        <header class="masthead mb-auto">
            <div class="inner">
                <h3 class="masthead-brand">$brand</h3>
                <nav class="nav nav-masthead justify-content-center">
HEREDOC;

        $coverNavFoot = <<<HEREDOC
                </nav>
            </div>
      </header>
HEREDOC;

        $coverNavHtml = $coverNavHead . $anchors . $coverNavFoot;
        return $coverNavHtml;
    }

    /**
     * alerts
     * @param string $type 'info', 'primary', 'secondary', 'warning', 'danger', 'success', 'light'
     * @param string $msg the message to be displayed in the alert box (use markup to <strong> or <bold> or <href> other)
     * @param boolean $dismiss if set to false, the alert box will not have a 'x' dismiss button, it cannot be dismissed.
     */
    public function alert($type = 'info', $msg = null, $zIndex = false, $dismiss = true)
    {
        $buttonHtml = <<<HEREDOC
        <button type="button" class="close" data-dismiss="alert">&times;</button>
HEREDOC;
        $dismissable = ($dismiss === true) ? "alert-dismissible" : '';
        $button = ($dismiss === true) ? $buttonHtml : '';
        $z = ($zIndex !== false) ? "style='z-index: $zIndex;'" : '';
        $alertHtml = <<<HEREDOC
            <div class="bs-component col-sm-10">
              <div class="alert $dismissable alert-$type" $z>
                $button
                $msg
              </div>
            </div>
HEREDOC;

        return $alertHtml;
    }

    public function jumbotron($title = null, $subTitle = null, $msg = null, $buttonDisplay = null)
    {
        $jumbotronOpen = <<<HEREDOC
        <div class="jumbotron">
            <h1 class="display-3">$title</h1>
            <p class="lead">$subTitle</p>
            <hr class="my-4">
            <p>$msg</p>
            $buttonDisplay
HEREDOC;

        $jumbotronButton = <<<HEREDOC
            <p class="lead">
            <a class="btn btn-primary btn-lg" href="#" role="button">$buttonDisplay</a>
            </p>
HEREDOC;

        $jumbotronClose = <<<HEREDOC
        </div>\n
HEREDOC;

        $jumbotronButton = ($buttonDisplay !== null) ? $jumbotronButton : '';
        $jumbotronHtml = $jumbotronOpen . $jumbotronButton . $jumbotronClose;

        return $jumbotronHtml;
    }

    public function card($title = null, $msg = null, $class = 'primary', $list = [], $links = [], $blank = false, $image = null, $footer = null)
    {
        $cardImg = <<<HEREDOC
        <img class="card-img-top" src="$image" alt="Card image cap">
HEREDOC;

        $image = ($image !== null) ? $cardImg : '';

        /** open card */
        $cardOpen = <<<HEREDOC
        <div class="card border-$class mb-3 text-left">
        $image
        <div class="card-header text-center">
        <h2 class="card-title text-center">$title</h2>
        </div>
        <div class="card-body text-center">
        <p class="card-text text-center">$msg</p>
        </div>
HEREDOC;


        /** list */
        $cardListOpen = <<<HEREDOC
        <ul class="list-group list-group-flush text-left">
HEREDOC;

        if (!empty($list)) {
            $cardListBody = '';
            foreach ($list as $key => $value) {
                $cardListItem = <<<HEREDOC
        <li class="list-group-item text-left">$key $value</li>
HEREDOC;
                $cardListBody .= $cardListItem;
            }
        }

        $cardListClose = <<<HEREDOC
         </ul>
HEREDOC;

        $cardList = (!empty($list)) ? $cardListOpen . $cardListBody . $cardListClose : '';
        /** end list */
        /** links */
        $cardLinksOpen = <<<HEREDOC
        <div class="card-body text-left">
        <ul class="list-group list-group-flush text-left">
HEREDOC;

        if (!empty($links)) {
            $blank = ($blank === true) ? "target='_blank'" : '';
            $cardLinksBody = '';
            foreach ($links as $href => $display) {
                $cardLinksItem = <<<HEREDOC
        <li class="list-group-item text-left"><a $blank href="$href" class="card-link text-left">$display</a></li><br />
HEREDOC;
                $cardLinksBody .= $cardLinksItem;
            }
        }

        $cardLinksClose = <<<HEREDOC
        </ul>
        </div>
HEREDOC;

        $cardLinks = (!empty($links)) ? $cardLinksOpen . $cardLinksBody . $cardLinksClose : '';
        /** end links */
        /** footer */
        $cardFooter = <<<HEREDOC
        <div class="card-footer text-muted">
        $footer
        </div>
HEREDOC;
        $cardFooter = ($footer !== null) ? $cardFooter : '';

        /** close card */
        $cardClose = <<<HEREDOC
        </div>\n
HEREDOC;

        $cardHtml = $cardOpen . $cardList . $cardLinks . $cardFooter . $cardClose;

        return $cardHtml;
    }
}

?>