Mautic.getNetworkFormAction = function(networkType) {
    // removes errors when network type properties has changed
    if (networkType && mQuery(networkType).val() && mQuery(networkType).closest('.form-group').hasClass('has-error')) {
        mQuery(networkType).closest('.form-group').removeClass('has-error');
        if (mQuery(networkType).next().hasClass('help-block')) {
            mQuery(networkType).next().remove();
        }
    }

    Mautic.activateLabelLoadingIndicator('monitoring_networkType');

    var query = "action=plugin:mauticSocial:getNetworkForm&networkType=" + mQuery(networkType).val();

    mQuery.ajax({
        url: mauticAjaxUrl,
        type: "POST",
        data: query,
        dataType: "json",
        success: function (response) {
            if (typeof response.html != 'undefined') {
                // pushes response into container element
                mQuery('#properties-container').html(response.html);

                // sends markup through core js parsers
                if (response.html != '') {
                    Mautic.onPageLoad('#properties-container', response);
                }
            }

        },
        error: function (request, textStatus, errorThrown) {
            Mautic.processAjaxError(request, textStatus, errorThrown);
        },
        complete: function() {
            Mautic.removeLabelLoadingIndicator();
        }
    });
};

/*
 * watches the compose field and updates various parts of the modal and text area
 */
Mautic.composeSocialWatcher = function() {
    // the text area
    var input = mQuery('#compose-tweet textarea');

    // on load
    Mautic.updateCharacterCount();

    // watch the text area keyup
    input.on('keyup', function(){
        Mautic.updateCharacterCount();
    });

    var pageId  = mQuery('#page select');
    var assetId = mQuery('#asset select');
    var handle  = mQuery('#handle button');

    pageId.on('change', function() {
        Mautic.insertSocialLink(pageId.val(), 'pagelink', false);
    });

    assetId.on('change', function() {
        Mautic.insertSocialLink(assetId.val(), 'assetlink', false);
    });

    handle.on('click', function() {
       Mautic.insertSocialLink(false, 'twitter_handle', true);
    });
};

/*
 * gets the count of the text area and returns (140 - count)
 */
Mautic.getCharacterCount = function() {
    var tweetLenght = 140;

    var length = mQuery('#compose-tweet textarea');

    return (tweetLenght - length.val().length);
};

/*
 * sets the content of the character count span
 */
Mautic.updateCharacterCount = function() {
    var tweetCount = Mautic.getCharacterCount();

    var countContainer = mQuery('#character-count span');

    countContainer.text(tweetCount);
};

/*
 * inserts a link placeholder into the text box.
 *
 * @id     the id of the link placeholder
 * @type   the type of link to insert
 * @skipId if the id is blank and this is true it'll still insert the link
 */
Mautic.insertSocialLink = function(id, type, skipId) {

    // if there is no id and skipID is false then exit
    if (! id && ! skipId) {
        return;
    }

    // if we need to skip the id state just leave it out
    if (skipId) {
        var link = '{' + type + '}';
    }
    else {
        var link = '{' + type + '=' + id + '}';
    }

    var textarea = mQuery('#compose-tweet textarea');
    var currentVal = textarea.val();
    var newVal = (currentVal) ? currentVal + ' ' + link : link;
    textarea.val(newVal);

    Mautic.updateCharacterCount();
};