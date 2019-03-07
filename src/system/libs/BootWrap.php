<?php

namespace yellowheroes\bugs\system\libs;

use yellowheroes\bugs\system\config as config;

/**
 * Class BootWrap
 * A PHP wrapper for (selected) Bootstrap components...
 *
 * Quickly generate (a html5 document with) Bootstrap components
 * in your web-project.
 *
 * @package yellowheroes\bootwrap\libs
 */
class BootWrap
{
    /**
     * @var string $htmlInit : opening block html5 page
     * @var string $meta     : html meta-data
     * @var string $styles   : html code CSS stylesheets (in <head>)
     * @var string $libs     : html code Bootstrap libraries, other javascript libraries (in <head>)
     * @var string $js       : html code additional javascript (libraries) and related CSS in <head>
     * @var string $other    : optional - any other html placed in <head>
     * @var string $title    : browser tab title
     * @var string $footer   : closing block html5 page
     */

    /* type hinting of properties will only be possible starting PHP7.4 */
    private $htmlInit = '';
    private $meta = '';
    private $styles = '';
    private $libs = '';
    private $js = '';
    private $other = '';
    private $title = '';
    private $footer = null;

    /**
     * BootWrap constructor.
     *
     * set the default html5 doctype elements
     * and pull-in the necessary Bootstrap libs (js and css)
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
     * set the opening block for html5 page
     *
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
     *
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
     *
     * @param array $styleSheets path to each style sheet
     *
     * @return void
     */
    public function setStyles($styleSheets = []): void
    {
        // default CSS (minimum requirement for Bootstrap to function)
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
     *
     * @param array $libs
     *
     * @return void
     */
    public function setLibs($libs = []): void
    {
        // default libraries (minimum requirement for Bootstrap to function)
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
     *
     * e.g. Bootstrap tooltips, or Bootstrap dropdowns, or some .js editor
     *
     * @param array $js
     *
     * @return void
     */
    public function setJs($js = []): void
    {
        foreach ($js as $key) $this->js .= <<<HEREDOC
$key\n
HEREDOC;
    }

    /**
     * anything else - css / js - to set (in <head> block)
     *
     * @param null $other
     *
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
     *
     * @param string $title defaults to 'bootwrap'
     *
     * @return void
     */
    public function setTitle($title = 'bootwrap'): void
    {
        $this->title = <<<HEREDOC
<title>$title</title>\n\n
HEREDOC;
    }

    /**
     * set the document footer
     *
     * the footer can be constructed to contain hyperlinks
     * format:
     *          category title  display txt     linked doc  display txt   linked doc
     *          ['general' => ['contact us' => 'home.php', 'about us' => 'about.php']]
     *
     * @param string        $copyRight      : optional copyright message
     * @param array         $hrefs          : optional hypertext links
     * @param string        $imageSrcPath   : optional image src path (e.g. logo)
     *
     * @return void
     */
    public function setFooter($copyRight = 'organisation', $hrefs = [], $imageSrcPath = ''): void
    {
        /** set default: copyright symbol and year */
        $copyRightSymbol = " &#169 ";
        $copyRightYear = date("Y");
        $copyRight = $copyRight . $copyRightSymbol . $copyRightYear; // append Copyright notice: c YYYY - to footer content

        /* construct the href - text-links - block */
        $links = ''; // initialize
        $image = ($imageSrcPath !== '') ? "<img class='float-right' src='$imageSrcPath' width='48px' height='48' style='margin: 10px;'>" : ''; // logo
        if (!empty($hrefs)) {
            $links = "<div class='row'>";
            $links .= "<div class='col'>$image</div>"; // logo
            foreach ($hrefs as $title => $textLink) { // each href list-block can have a title
                $links .= "<div class='col'>"; // start div-column for each list-block of hrefs
                foreach ($textLink as $display => $link) { // the actual links
                    $title = $title ?? null;
                    $title = strtoupper($title);
                    $href = $this->href($link, $display);
                    $links .= <<<HEREDOC
            $title
            <ul class="list-unstyled quick-links" style="line-height: 10px; font-size: 0.8em;">
            <li style="text-left">
            $href
            </li>
            </ul>\n
HEREDOC;
                    $title = null; // render title only once for each block or category of hrefs
                }
                $links .= "</div>"; // end div-column for a list-block of hrefs
            }
            $links .= "</div>"; // end div-row - all hypertext link blocks are generated
        }
        $links = ($links !== '') ? "<div class='text-muted' style='color: #FFFFFF !important;'>$links</div><div><br /></div>" : '';

        $this->footer = <<<HEREDOC
</main>

<footer class="footer" style="margin-top: 80px;">
    <div class="container-fluid bg-dark">
    <!-- <div><hr class="bg-primary"/></div> -->
        $links
        <div class="text-muted text-center" style="color: #FFFFFF !important;">$copyRight</div>
    </div>
</footer>

</body><!-- end body element, opening tag in header -->
</html><!-- end html element, opening tag in header -->
HEREDOC;
    }

    /**
     * @param string|null $title
     * @param array|null  $styleSheets
     * @param array|null  $libs
     * @param array|null  $js
     * @param null        $other
     *
     * @return string
     */
    public function head($title = null, $styleSheets = null, $libs = null, $js = null, $other = null): string
    {
        $invoke = (isset($styleSheets)) ? $this->setStyles($styleSheets) : null;
        $invoke = (isset($libs)) ? $this->setLibs($libs) : null;
        $invoke = (isset($js)) ? $this->setJs($js) : null;

        $head = <<<HEREDOC
$this->htmlInit

		<!-- required meta tags -->
        $this->meta

		<!-- CSS -->
        $this->styles

        <!-- Libraries - jQuery, Popper.js, Bootstrap.js -->
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
     * Modals are built with HTML, CSS, and JavaScript. They’re positioned over everything else in the document and
     * remove scroll from the <body> so that modal content scrolls instead.
     * Clicking on the modal “backdrop” will automatically close the modal.
     * Bootstrap only supports one modal window at a time. Nested modals aren’t supported as we believe them to be poor
     * user experiences. Modals use position: fixed, which can sometimes be a bit particular about its rendering.
     * Whenever possible, place your modal HTML in a top-level position to avoid potential interference from other
     * elements. You’ll likely run into issues when nesting a .modal within another fixed element. Once again, due to
     * position: fixed, there are some caveats with using modals on mobile devices. See our browser support docs for
     * details. Due to how HTML5 defines its semantics, the autofocus HTML attribute has no effect in Bootstrap modals.
     * To achieve the same effect, use some custom JavaScript.
     *
     * @param string|null $title
     * @param string|null $msg
     * @param bool        $showOnload
     * @param string      $id
     *
     * @return string return Bootstrap modal HTML
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
     *
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

        $confirmSubmit = ($confirmationDialog === true) ? $this->confirmationDialog($submitDisplay, '', 'confirmationDialog', 'confirm', 'Please confirm...', false) : false;

        /**
         *  store either 'normal' or 'confirmation' button in $submitButton
         *
         * IMPORTANT: the normal button has field-name 'submit', whereas the 'confirmation' button field-name is 'confirm'
         *            so when checking e.g. $_POST, make sure you're checking the correct field-name.
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
     * @param string $submitDisplay     set it to false if no form submit button should be rendered, anything else will
     *                                  be displayed on button a typical use-case for a form without a submit button is
     *                                  where we substitute it for a confirmDialog button (are you sure...) where
     *                                  field-name 'submit' becomes 'confirm' (e.g. delete actions, where we need to be
     *                                  sure this is what user wants).
     *
     * @param array inputFields         array that holds an array for each input-field: ['type', 'name', 'id', 'value',
     *                                  'placeholder', options[]]
     *
     *                                  'type' in   case formfield:     set 'type' to - 'text' or 'email' or
     *                                  'password', or...
     *
     *                                      case selectbox:     set 'type' to - 'select' - select type html-block
     *
     *                                      case radiobuttons:  set 'type' to - 'radio'
     *
     *                                      case checkboxes:    set 'type' to - 'checkbox'
     *
     *                                      case file:          set 'type' to - 'file'
     *
     *                                      'name' is the reference to retrieve the user-input in the $_POST(default)
     *                                      or $_GET array
     *
     *                                      'id' is the identifier, can be used for e.g. javascript or CSS reference
     *
     *                                      'value' is the initial value that can be set in the input box (can be handy
     *                                      in setting 'hidden' input box values).
     *
     *                                      'placeholder' shows in the input field as a 'hint'
     *
     *                                      options[] - e.g. set 'required' on a field, or define select-list-items, or
     *                                      set checked on a default choice radio button.
     *
     * EXAMPLE:
     * $inputFields =  [
     *                  ['text', 'slug', 'slug', "", 'enter article slug'],
     *                  ['hidden', 'existingArticleId', 'existingArticleId', $existingArticleId, ""],
     *                  ['hidden', 'store', 'store', $store, ""] // a same-type form-field needs to be 'marked' with an
     *                  '*'
     *                 ];
     * $form = (new libs\BootWrap())->form($inputFields, false); // 'false' as we do not need a submit button, we have
     * a seperate button to trigger jQuery code
     *
     * @backHref    a 'back' button, just set the href for the target location.
     */

    /**
     * @param array         $inputFields            : text, password, select, hidden...
     * @param string        $submitDisplay          : the text displayed on the submit button
     * @param string        $method                 : POST or GET
     * @param string|bool   $action                 : the script that gets invoked on submit, or if 'false' no action at all(no page refresh)
     * @param string        $formId                 : the #id of the form
     * @param bool          $backHref               : a back-button href link
     * @param string        $backDisplay            : the text displayed on the back button (defaults to 'Back')
     * @param array         $confirmationDialog     : [0] == false, no confirmation dialog triggered, [0] == true - a confirmation dialog is triggered
     *                                                [1] == true, 'text-href' [1] == false, 'button-href'
     *
     * @return string                               : the form html
     */
    public function form($inputFields = [], $submitDisplay = 'submit', $method = 'POST', $action = "#", $formId = "formId", $backHref = false, $backDisplay = 'Back', $confirmationDialog = [false, true])
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
            $fieldValue = $value[3] ?? ""; // can be useful to set initial value for textarea or for hidden form fields where a field value can be carried-over to next page
            $placeholder = $value[4] ?? "";
            $label = ($type !== 'hidden') ? $value[5] : "";
            $options = $value[6] ?? null; // $value[6] contains an array with options(for e.g. to set 'required' or for select or radio buttons)

            /** type: text or password or email */
            if ($type === 'text' || $type === 'password' || $type === 'email' || $type === 'hidden') {
                /* hidden form fields may not take screen space */
                $style = ($type === 'hidden') ? "style='display: none'" : null;
                $formFields .= <<<HEREDOC
        <div class="form-group" $style>
            <label for="$id" class="col-sm-2 control-label">$label</label>
            <div class="col-sm-10">
            <input type="$type" class="form-control" name="$name" id="$id" value="$fieldValue" placeholder="$placeholder" $options[0]>
            </div>
        </div>\n
HEREDOC;
            }

            /* type textarea */
            if($type === 'textarea') {
                $formFields .= <<<HEREDOC
        <div class="form-group" $style>
            <label for="$id" class="col-sm-2 control-label">$label</label>
            <div class="col-sm-10">
            <textarea class="form-control" id="$id" name="$name" rows="10" $options[0]>$fieldValue</textarea>
            </div>
        </div>
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
        <button type="button" class="btn btn-primary pull-right" onclick="location.href='$backHref';">$backDisplay</button>\n
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

        // $confirmationDialog
        $href = ($confirmationDialog[1] === true) ? true : false; // a button (if false), or a href text (if true)
        $confirmSubmit = ($confirmationDialog[0] === true) ? $this->confirmationDialog($submitDisplay, '', 'confirmationDialog', 'confirm', 'Please confirm...', $href) : false;

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
     * @param string $method    : POST(default) or GET
     * @param string $name      : the field-name we pick up wih $_POST['field-name'] or $_GET['field-name']
     * @param bool   $action    : false == no action attribute, i.e. ensure we do not refresh page (AJAX call)
     *
     * @return string           : the search-form html
     */
    public function searchForm($method = 'POST', $name = 'search', $action = false): string
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
     * dropdown items only work with js-plugin - we include the functionality in Header.php
     * <script>
     *  $(function () {
     *       $('.dropdown-toggle').dropdown()
     *    });
     *   </script>
     *
     * @param string $menuDisplay
     * @param array  $navItems  ['display1'=>'href1', 'display2'=>'href2' etc...]
     *
     *                                  if you want to insert a divider (horizontal ruler)
     *                                  just insert ''=>''
     *                                  e.g.
     *                                  ['display1'=>'href1', ''=>'', 'display2'=>'href2'] renders a divider between
     *                                  the two anchors
     * @param string $activeNav 'nav-item' or 'nav-item-active'
     */
    /**
     * @param string $menuDisplay
     * @param array  $navItems
     * @param string $activeNav
     * @param string $class
     * @param string $size
     *
     * @return string
     */
    public function dropDown($menuDisplay = 'dropdown', $navItems = [], $activeNav = '', $class = 'primary', $size = 'md'): string
    {
        $dropDownItems = null;
        /*
         * we encountered a problem highlighting the drop-down menu button ($menuDisplay) on selection
         * e.g. when user clicks drop-down button 'Dashboard', trying to highlight it with class 'active'
         * or 'nav-item active' doesn't work, so we set it manually here.
         */
        $textNormalColor = "color: #FFFFFF;"; // not selected, we set it to grey-blue
        $textActiveColor = "color: #FFC000;"; // selected, highlight text
        //$textNormalColor = "color: " . config\Config::TXTCOLOR_NORMAL_NAV;
        //$textActiveColor = "color: " . config\Config::TXTCOLOR_ACTIVE_NAV;
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
     * @param string $href      link's destination
     * @param string $activeNav set to a nav-item display value: e.g. 'home' to set the active button (highlighted) for
     *                          the 'home' page-view
     * @param string $display   text displayed on button
     * @param string $class     primary, secondary, success, danger, warning, info, light, dark
     * @param string $size      'lg' for large, 'sm' for small
     * @param string $type      'button' for a button-type nav-button
     *
     * @return string $navButtonHtml    : navigation button html
     */
    public function navButton($display = 'click me', $href = '#', $activeNav = null, $type = null, $class = 'primary', $size = null): string
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

    /**
     * BootWrap::actionButton()
     * Action buttons are normally not used in navigation but in e.g. forms, dialogs etc.
     *
     * The client can invoke a 'filled'(default) or 'outlined' button
     * Action buttons can be turned into navigation buttons(see BootWrap::navButton()), like so:
     * &ltbutton type="button" class="btn btn-outline-primary" onclick="location.href='$url';">navigational
     * action&lt/button&gt.
     *
     * @param string $display : text displayed on button
     * @param string $class   : primary, secondary, success, danger, warning, info, light, dark
     * @param bool   $outline : default solid fill, set to true if you want an outline-styled button
     *
     * @return string         : action-button html
     */
    public function actionButton(string $display = 'click me', string $class = 'primary', bool $outline = false): string
    {
        $actionButtonHtml = '';

        $button = <<<HEREDOC
        <button type="button" class="btn btn-$class">$display</button>\n
HEREDOC;

        $buttonOl = <<<HEREDOC
        <button type="button" class="btn btn-outline-$class">$display</button>\n
HEREDOC;

        // does the user want a filled / outline button
        $actionButtonHtml = ($outline !== true) ? $button : $buttonOl;
        return $actionButtonHtml;
    }

    public function href($link = null, $display = 'click me', $class = 'primary', $cssClass = null)
    {
        /*$hrefHtml = <<<HEREDOC
       <span class="$cssClass" style='margin-left: 10px;'><a href="$link">$display</a></span>
HEREDOC;*/
        $hrefHtml = <<<HEREDOC
       <span class="$cssClass"><a href="$link">$display</a></span>
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
     * @param array  $navItems []     set each nav-item's display-name (key) and href (value)
     *
     *                                    display  href    display     href
     *                              e.g. ['home'=>'index', 'contact'=>'contact']
     *
     * @param string $type     'button' for a button-type nav-button
     * @param string $class    primary, secondary, success, danger, warning, info, light, dark
     * @param string $size     'lg' for large, 'sm' for small, md for medium
     *
     * @param alignment             place the navbar at 'top', 'bottom', or make it 'sticky' (scroll with content)
     * @param logo                  logo[0]=display text, logo[1]=href to image
     *
     *
     * EXAMPLE: set appropriate nav-items dependent on the user profile: authorised / not authorised
     * $nonAuthUser = ['home'=>'index', 'login'=>'login', 'contact us'=>'contact'];
     * $authUser = ['home'=>'index', 'quill'=>'quill', 'repo'=>'repo', 'contact us'=>'contact', 'register new
     * user'=>'dashboard/register', 'logout'=>'logout'];
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
     * NEW navBar
     * with navItems['type'=>['display', 'href'], 'type'=>['display', 'href']
     * because we want to be able to add multiple types (button, drop-down, ...)
     *
     */

    /**
     * Bootstrap component          nav
     *
     * BootWrap::navBarNavButtons() custom-made(YH) navigation bar with navButtons
     *
     * @param array  $navItems  []     set each nav-item's display-name (key) and href (value)
     *
     *                              and if you want to add a dropdown menu, enter a [] with the dropdown menu items as
     *                              follows:
     *
     *                                    display  href    display     href             menu-display         display
     *                                            href            display             href e.g. ['home'=>'index',
     *                                            'contact'=>'contact',
     *                                            'dropdownMenuDisplay'=>['dropdownitem1'=>'dropdownhref1',
     *                                            'dropdowndisplay2'=>'dropdownhref2', etc...]]
     *
     *                              the function will recognise the dropdown as the $value === type array. This
     *                              triggers the function to call Bootwrap::dropdown().
     *
     * @param string $activeNav set to a nav-item display value: e.g. 'home' to set the active button (highlighted) for
     *                          the 'home' page-view
     * @param string $class     primary, secondary, success, danger, warning, info, light, dark
     * @param string $size      'lg' for large, 'sm' for small, md for medium
     *
     * @param alignment             place the navbar at 'top', 'bottom', or make it 'sticky' (scroll with content)
     * @param logo                  logo[0]=display text, logo[1]=href to image
     *
     * @return string               navigation bar html
     */
    public function navBar($navItems = [], $activeNav = null, $type = null, $class = 'primary', $size = 'md', $textColor = 'dark', $bgColor = 'dark', $alignment = 'top', $logo = [], $userName = 'visitor', $toolTip = null, $location = null, $search = false): string
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

        $logoTxt = $logoSrc = $logoHref = ''; // initialize
        if (!empty($logo)) {
            $logoTxt = $logo[0]; // e.g. organisation name / company name
            $logoSrc = $logo[1]; // the path to the logo image
            $logoHref = $logo[2]; // link when the logo is clicked
        }
        $navBarHtml = <<<HEREDOC
<nav class="navbar $alignment navbar-expand-lg navbar-$textColor p-3 bg-$bgColor">
    <img src="{$logoSrc}" width="24" height="24"><a class="navbar-brand p-2" href="{$logoHref}">{$logoTxt}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
 <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="nav navbar-nav">
$buttons
    </ul>
 $searchFormHtml
    <div class='col pull-right text-right'>
    <p class="navbar-text text-right pull-right text-white navbar-right m-3" data-toggle="tooltip" data-placement="auto" title="{$toolTip[0]}"><i class="fa fa-user-circle-o"></i><span class='pull-right'>$userName</span></p>
    <p class="navbar-text text-right pull-right text-white navbar-right m-3" data-toggle="tooltip" data-placement="auto" title="{$toolTip[1]}"><i class="fa fa-map-marker"></i><span class ='pull-right'>$location</span></p>  
    </div>
   </div>
</nav>\n
HEREDOC;

        return $navBarHtml;
    }
    /**
     * end NEW navBar
     */

    /**
     * Bootstrap component          nav
     *
     * BootWrap::navTabs()          navigate using tabs - content loaded with jQuery Ajax
     *
     * @param array  $tabs                      set element '#id' and tab display-name for each 'tab'
     *
     *                                     DOM      tab      DOM          tab
     *                                      id    display     id        display
     *                              e.g. ['home'=>'home', 'contact'=>'contact us']
     *
     * @param array  $pageContent               the content that will be rendered on each 'tab' (page)
     *                                          argument can be a simple string or a file (e.g. home.php, contact.html,
     *                                          something.txt...) e.g. $pageContent = ['Site Under Construction',
     *                                          'Something about the team...', 'index3.php']; The first argument is
     *                                          content that will be rendered on the first 'tab', the second arg. is
     *                                          content for the second 'tab', etc.
     *
     * @param string $alignment                 default the tabs render from the left
     *                                          options:    center
     *                                          right
     *                                          fill (extend the full available width)
     *                                          stack  (stacked tabs)
     *
     * @return string $navTabHtml       : navigation tab html
     */
    public function navTabs($tabs = [], $pageContent = [], $alignment = null): string
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
     * @param array  $pills                     set element '#id' and tab display-name for each 'tab'
     *
     *                                     DOM      tab      DOM          tab
     *                                      id    display     id        display
     *                              e.g. ['home'=>'home', 'contact'=>'contact us']
     *
     * @param array  $pageContent               the content that will be rendered on each 'tab' (page)
     *                                          argument can be a simple string or a file (e.g. home.php, contact.html,
     *                                          something.txt...) e.g. $pageContent = ['Site Under Construction',
     *                                          'Something about the team...', 'index3.php']; The first argument is
     *                                          content that will be rendered on the first 'tab', the second arg. is
     *                                          content for the second 'tab', etc.
     *
     * @param string $alignment                 default the pills render from the left
     *                                          options:    'center'
     *                                          'right'
     *                                          'fill' (extend the full available width)
     *                                          'stack'  (stacked pills - i.e. a vertical navigation menu)
     *                                          (if 'stack' is selected, client can set the width of the buttons with
     *                                          $grid)
     *
     * @param int    $grid                      for vertically stacked pills, set relative width for pills (# columns
     *                                          out of total 12) default 3 columns for pills, and remaining 9 columns
     *                                          for the content
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
     * $id = the id of the element - IMPORTANT when e.g. rendering a list of blog-articles need to set individual #id's
     * for each <div> in the list so the dialog renders in the right place
     * $msg = the confirmation message
     *
     * two field 'name' s : 'confirm' and 'cancel'
     */

    /**
     * @param string $display           : the text of the href link (or button) - e.g. 'delete article' or 'delete file'
     * @param string $action            : the target script that is invoked on 'confirm' or 'cancel' (there are two submit buttons)
     * @param string $id                : the id of the element
     *                                      IMPORTANT - when e.g. rendering a list of items
     *                                      you need to set individual #id's for each item <div>
     *                                      in the list, so the dialog renders in the right place.
     * @param string $name              : the confirm field-name we can pick up with $_POST[] or $_GET[]
     * @param string $msg               : the confirmation message ('are you sure?')
     * @param bool   $href              : set this to false if you want to render a button (i.e. not a hypertext link)
     *
     * @return string
     */
    public function confirmationDialog($display = '', $action = '', $id = 'confirmationDialog', $name = 'confirm', $msg = 'Please confirm...', $href = true): string
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

        $link = ($href !== '') ? $href : $button;

        /*
         * note: we declare a unique name for the confirm field, as we may have multiple confirmation forms on one page.
         * We can then capture a click event on $_POST['someUniqueConfirmName'], which differs from a click on $_POST['someOtherUniqueConfirmName'].
         */
        $confirmationDialogHtml = <<<HEREDOC
<p>
  $link
</p>
<div class="collapse" id="$id">
  <div class="card card-body">    
    <p>$msg</p>
    <div>
      <form method='post' action='$action'>
        <input type='submit' class="btn btn-primary" name='$name' value='confirm'>
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
     *
     * @param array $navItems       : key=display, value=link(href)
     * @param string $activeNav     : highlight the active VIEW nav-button text
     * @param null  $brand          : e.g. a company logo that's rendered left of the navbar
     *
     * @return string               : a nav-bar (styled to Bootstrap class 'cover-container')
     */
    public function coverNav($navItems = [], $activeNav = '', $brand = null): string
    {
        $active = null;
        $anchors = null;
        foreach ($navItems as $key => $value) {
            $display = $key;
            $href = $value;
            $active = ($href === $activeNav) ? 'active' : null;
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
     * BootWrap::alert() wraps BootStrap alert component
     * use it to provide contextual feedback messages for typical user actions
     *
     * @param string   $msg     : the message to be displayed in the alert box
     * @param string   $type    : 'info', 'primary', 'secondary', 'warning', 'danger', 'success', 'light'
     * @param bool     $dismiss : if set to false, the alert cannot be dismissed.
     * @param int|null $zIndex  : overlapping elements with a larger z-index cover those with a smaller one.
     *
     * @return string           : alert-html
     */
    public function alert(string $msg = '', string $type = 'info', bool $dismiss = true, int $zIndex = null): string
    {
        $buttonHtml = <<<HEREDOC
        <button type="button" class="close" data-dismiss="alert">&times;</button>
HEREDOC;
        $dismissable = ($dismiss === true) ? "alert-dismissible" : '';
        $button = ($dismiss === true) ? $buttonHtml : '';
        $z = ($zIndex !== null) ? "style='z-index: $zIndex;'" : '';
        $alertHtml = <<<HEREDOC
              <div class="bs-component col-sm-10 alert $dismissable alert-$type" $z>
                $button
                $msg
              </div>
HEREDOC;
        return $alertHtml;
    }

    /**
     * @param null|string $title
     * @param null|string $subTitle
     * @param null|string $msg
     * @param null|string $buttonDisplay
     *
     * @return string : jumbotron-html ready for echoing
     */
    public function jumbotron($title = null, $subTitle = null, $msg = null, $buttonDisplay = null): string
    {
        $jumbotronHtml = '';

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

    /**
     * BootWrap::card() wraps BootStrap card component
     * cards provide a flexible and extensible content container with multiple variants and options.
     *
     * @param string|null   $title
     * @param string|null   $msg
     * @param string        $class
     * @param array         $list
     * @param array         $links
     * @param bool          $blank
     * @param array|null    $image
     * @param string|null   $footer
     *
     * @return string                   : card-html
     */
    public function card($title = null, $msg = null, $class = 'primary', $list = [], $links = [], $blank = false, $image = [], $footer = null): string
    {
        $cardImg = <<<HEREDOC
        <img class="card-img-top" src="$image[0]" style = "width: $image[1]%;" alt="card image cap">
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