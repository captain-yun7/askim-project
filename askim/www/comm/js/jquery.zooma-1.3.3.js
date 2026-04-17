/**
 * ##########################################################################################
 * Image zoom (magnification) plugin for jQuery library http://alexeyenko.net/projects/zooma/
 *
 * Thanks to Justin Robert Wehrman (jwehrman@exertionist.com),
 * the developer of 'imageDetail' plugin http://exertionist.com/ImageDetail/
 *
 * Zooma jquery plugin page http://plugins.jquery.com/project/zooma
 *
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * v1.3.3 May       2011 - Fixed unbind - submitted patch from Justin Tanner
 * v1.3.2 January   2009 - Fixed deactivate method
 * v1.3.1 January   2009 - Added jQuery v 1.4 support
 * v1.3.0 January   2009 - Added ability to switch auto-hide off (added optional settings parameter), exitCallback function was added to settings
 * v1.2.3 December  2009 - Fixed viewfinder issues when zoom thumb is placed in absolutely positioned blocks. viewfinder element now must be placed in the root of html document
 * v1.2.2 November  2009 - Fixed exit if zoom viewfinder has same width/height as original image 
 * v1.2.1 October   2009 - Added loading... indicator. Fixed bug when large image is smaller then zoom div.
 * v1.1.1 October   2009 - Viewfinder block alignment was updated.
 * v1.1.0 October   2009 - Added viewfinder box. Code refactor (code becomes Object oriented).
 * v1.0.1 October   2009 - Zoom becomes faster: better mouse events management.
 * v1.0.0 October   2009.
 * author: Ignat Alexeyenko, http://alexeyenko.net
 * ##########################################################################################
 */
(function ($)
{

    //////////////////////////////////////////////////////////////////////////////////////////////////
    ///  Utility methods and classes  ////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////

    function ImageData()
    {
        this.width = 0;
        this.height = 0;
        // cache some constant data to simplify further calculations and make zooma faster
        this.viewfinderWidth = 0;
        this.viewfinderHeight = 0;
        this.halfViewfinderWidth = 0;
        this.halfViewfinderHeight = 0;
        this.widthDiff = 0;
        this.heightDiff = 0;
        this.thumbImgPositionLeft = 0;
        this.thumbImgPositionRight = 0;
        this.thumbImgPositionTop = 0;
        this.thumbImgPositionBottom = 0;
        this.thumbImgOffsetLeft = 0;
        this.thumbImgOffsetRight = 0;
        this.thumbImgOffsetTop = 0;
        this.thumbImgOffsetBottom = 0;
        this.viewfinderBorderTop = 0;
        this.viewfinderBorderLeft = 0;
        this.viewfinderBorderBottom = 0;
        this.viewfinderBorderRight = 0;

        this.loaded = false;

        this.getHeight = function()
        {
            return this.height;
        },

                this.getWidth = function()
                {
                    return this.width;
                },

                this.isLoaded = function()
                {
                    return this.loaded;
                };

    }

    /**
     * We need to detect size of the large image. The browser can not determine size of the image
     * before the image was loaded.
     *
     * This class will use callback function that will called when image is loaded
     *
     * @param src URL (String) with large image that will be used in zoom block.
     * @param calllbackFunction to call and path the imageData object there
     */
    function LoaderHeleper(src, calllbackFunction)
    {
        var imageData = new ImageData();

        var imgToLoad = new Image();
        $(imgToLoad).load(function()
        {
            imageData.width = imgToLoad.width;
            imageData.height = imgToLoad.height;

            imageData.loaded = true;
            calllbackFunction(imageData);

        });
        $(imgToLoad).attr('src', src);

        return imageData;
    }

    /**
     * Checks whether position is inside min+epsilon max+epsilon interval. If not,
     * @param position current position
     * @param min minimun allowed position
     * @param max maximum allowed position
     * @param epsilon addtition border to specified min & max
     */
    function correctPositionByEpsilonBorder(position, min, max, epsilon)
    {
        // check min border (position - min < epsilon)
        var allowedMin = min + epsilon;
        if (position < allowedMin)
        {
            return allowedMin;
        }
        // check max border (max - position < epsilon)
        var allowedMax = max - epsilon;
        if (position > allowedMax)
        {
            return allowedMax;
        }

        return position;
    }

    function checkAndPrepareSettings(settings)
    {
        if (settings == null || typeof (settings) == undefined)
        {
            settings = new Object();
        }

        if (settings.autoDeactivate == null || typeof (settings.autoDeactivate) == undefined)
        {
            settings.autoDeactivate = true;
        }

        if (settings.zoomViewfinder == null || typeof (settings.zoomViewfinderName) == undefined)
        {
            settings.zoomViewfinderName = 'zoomViewfinder';
        }

        if (settings.exitCallback == null || typeof (settings.exitCallback) == undefined)
        {
            settings.exitCallback = function()
            {

            };
        }
        return settings;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////
    ///  Utility methods and classes ends  ///////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////

    $.fn.zooma = function (thumbnailID, fullID, fullImageUrl, settings)
    {
        $.extend(this, {

            /**
             * Deactivates the zooma plugin
             */
            deactivate : function ()
            {
                // Create vars for Thumbnail and Full Containers
                fullImageContainer.hide();
                viewAreaDiv.hide();

                $(document).unbind('mousemove', mouseMoveListener);

                IDa.unbind('mouseover');
                viewAreaDiv.unbind('mouseover');
            },

            /**
             * Activates the zooma plugin
             */
            activate : function()
            {
                // check that settings parameter exists

                // Set Full Image as Full Container background-image
                $(fullImageContainer).css({
                    // Get Thumbnail Image path // Create Full Image path from Thumbnail
                    backgroundImage: 'url(' + fullImageUrl + ')',
                    backgroundRepeat: 'no-repeat',
                    backgroundPosition: '50% 50%'
                });

                fullImageContainer.show();

                // We want to display zoom block only when mouse is over thumbnail image
                isVisibleZoomBlock = false;

                // we should deactivate the zoom window when mouse pointer leaves the thumbnail,
                // but when we click zoom, mouse pointer will be outside the window - so the flag is used

                IDa.bind('mouseover', handleMouseEnteresZoomArea);
                viewAreaDiv.bind('mouseover', handleMouseEnteresZoomArea);
            }
        });


        var isVisibleZoomBlock = false;

        // Create vars for Thumbnail and Full Containers
        var thumbImageContainer = $('#' + thumbnailID);
        var fullImageContainer = $('#' + fullID);

        var IDa = thumbImageContainer.find('a');

        var imgElement = thumbImageContainer.find('img');
        var thumbWidth = imgElement.width();
        var thumbHeight = imgElement.height();

        var loadingDiv = fullImageContainer.find('div.zoomLoading');

        // following width & height are related to container height,
        // but can be changed if large image less in heigh or width than container to simplify caclulation logic
        var mutableFullWidth = fullImageContainer.width();
        var mutableFullHeight = fullImageContainer.height();

        // following width will not be changed (see mutableFullWidth/mutableFullHeight) 
        var originalFullWidth = fullImageContainer.width();
        var originalFullHeight = fullImageContainer.height();


        // mid width/height of thumbnail container
        var MidDisplayWidth = mutableFullWidth / 2;
        var MidDisplayHeight = mutableFullHeight / 2;

        var thumbImgPositionLeft = imgElement.position().left;
        var thumbImgPositionRight = imgElement.position().left + thumbWidth;
        var thumbImgPositionTop = imgElement.position().top;
        var thumbImgPositionBottom = imgElement.position().top + thumbHeight;

        var thumbImgOffsetLeft = imgElement.offset().left;
        var thumbImgOffsetRight = imgElement.offset().left + thumbWidth;
        var thumbImgOffsetTop = imgElement.offset().top;
        var thumbImgOffsetBottom = imgElement.offset().top + thumbHeight;

        var mouseEnteredZoomArea = false;

        // user can upload image that will be less than zoom block in width or height
        var widthCutted = false;
        var heightCutted = false;


        settings = checkAndPrepareSettings(settings);

        var autoDeactivate = settings.autoDeactivate;
        var zoomViewfinderName = settings.zoomViewfinderName;
        var exitCallbackFunction = settings.exitCallback;

        var viewAreaDiv = $('#' + zoomViewfinderName);

        var loadedImgData = new LoaderHeleper(fullImageUrl, function(imageData)
        {
            imageData.viewfinderBorderTop = parseInt(viewAreaDiv.css('borderTopWidth'));
            imageData.viewfinderBorderLeft = parseInt(viewAreaDiv.css('borderLeftWidth'));
            imageData.viewfinderBorderBottom = parseInt(viewAreaDiv.css('borderBottomWidth'));
            imageData.viewfinderBorderRight = parseInt(viewAreaDiv.css('borderRightWidth'));


            if (imageData.getWidth() != 0 && imageData.getHeight() != 0)
            {
                if (mutableFullWidth > imageData.getWidth())
                {
                    mutableFullWidth = imageData.getWidth();
                    widthCutted = true;
                }

                if (mutableFullHeight > imageData.getHeight())
                {
                    mutableFullHeight = imageData.getHeight();
                    heightCutted = true;
                }

                imageData.viewfinderWidth = Math.round(thumbWidth * mutableFullWidth / (imageData.getWidth()));
                imageData.viewfinderHeight = Math.round(thumbHeight * mutableFullHeight / (imageData.getHeight()));
                if (widthCutted)
                {
                    imageData.viewfinderWidth = imageData.viewfinderWidth - (imageData.viewfinderBorderLeft + imageData.viewfinderBorderRight);
                }
                if (heightCutted)
                {
                    imageData.viewfinderHeight = imageData.viewfinderHeight - (imageData.viewfinderBorderTop + imageData.viewfinderBorderBottom);
                }

                imageData.halfViewfinderWidth = imageData.viewfinderWidth / 2.0;
                imageData.halfViewfinderHeight = imageData.viewfinderHeight / 2.0;
                // get full / thumb difference
                imageData.widthDiff = imageData.getWidth() / thumbWidth;
                imageData.heightDiff = imageData.getHeight() / thumbHeight;
            }

            imageData.thumbImgPositionLeft = thumbImgPositionLeft;
            imageData.thumbImgPositionRight = thumbImgPositionRight;
            imageData.thumbImgPositionTop = thumbImgPositionTop;
            imageData.thumbImgPositionBottom = thumbImgPositionBottom;

            imageData.thumbImgOffsetLeft = thumbImgOffsetLeft;
            imageData.thumbImgOffsetRight = thumbImgOffsetRight;
            imageData.thumbImgOffsetTop = thumbImgOffsetTop;
            imageData.thumbImgOffsetBottom = thumbImgOffsetBottom;

            viewAreaDiv.css('width', imageData.viewfinderWidth);
            viewAreaDiv.css('height', imageData.viewfinderHeight);
            // put zoom div in the center of the thumbnail element
            viewAreaDiv.css('left', thumbImgOffsetLeft + Math.round(thumbWidth / 2.0 - (imageData.viewfinderWidth + imageData.viewfinderBorderLeft + imageData.viewfinderBorderRight) / 2.0));
            viewAreaDiv.css('top', thumbImgOffsetTop + Math.round(thumbHeight / 2.0 - (imageData.viewfinderHeight + imageData.viewfinderBorderTop + imageData.viewfinderBorderBottom) / 2.0));

            viewAreaDiv.show();
            loadingDiv.hide();
        });

        if (!loadedImgData.isLoaded())
        {
            loadingDiv.show();
        }

        // check wheher we need to close zoom mode
        function mouseMoveListener(event)
        {
            // check whether mouse pointer leaves thumbnail block
            var mouseIsOutZoomArea = (event.pageX < thumbImgOffsetLeft || event.pageX > thumbImgOffsetRight || event.pageY < thumbImgOffsetTop || event.pageY > thumbImgOffsetBottom);
            if (autoDeactivate && mouseEnteredZoomArea && mouseIsOutZoomArea)
            {
                $(document).unbind('mousemove', mouseMoveListener);

                // Create vars for Thumbnail and Full Containers
                fullImageContainer.hide();
                viewAreaDiv.hide();

                exitCallbackFunction();
            }
            else
            {
                if (!mouseIsOutZoomArea)
                {
                    handleMouseMove(event);
                }
            }
        }

        function handleMouseEnteresZoomArea()
        {
            mouseEnteredZoomArea = true;

//            if (!loadedImgData.isLoaded())
//            {
//                return;
//            }

            if (!isVisibleZoomBlock)
            {
                fullImageContainer.show();
                viewAreaDiv.show();
                isVisibleZoomBlock = true;
                $(document).bind('mousemove', mouseMoveListener);
            }
        }

        // Thumbnail Mousemove Event
        function handleMouseMove(event)
        {
            // Check that image was already loaded. If so - continue
            if (loadedImgData.isLoaded())
            {
                var mouseX = event.pageX;
                var mouseY = event.pageY;

                var verticalViewfinderBorder = loadedImgData.viewfinderBorderTop + loadedImgData.viewfinderBorderBottom;
                var horizontalViewfinderBorder = loadedImgData.viewfinderBorderLeft + loadedImgData.viewfinderBorderRight;

                var mouseXFixed = correctPositionByEpsilonBorder(mouseX, loadedImgData.thumbImgOffsetLeft, loadedImgData.thumbImgOffsetRight - horizontalViewfinderBorder, loadedImgData.halfViewfinderWidth);
                var mouseYFixed = correctPositionByEpsilonBorder(mouseY, loadedImgData.thumbImgOffsetTop, loadedImgData.thumbImgOffsetBottom - verticalViewfinderBorder, loadedImgData.halfViewfinderHeight);

                var viewfinderLeft = Math.round(mouseXFixed - loadedImgData.halfViewfinderWidth);
                var viewfinderTop = Math.round(mouseYFixed - loadedImgData.halfViewfinderHeight);
                //
                viewAreaDiv.css('left', viewfinderLeft);
                viewAreaDiv.css('top', viewfinderTop);

                var bgy = 0;
                var bgx = 0;

                if (widthCutted)
                {
                    bgx = Math.round((originalFullWidth - loadedImgData.getWidth()) / 2.0);
                }
                else
                {
                    // set Full Image background-position
                    bgx = Math.round((Math.round(mouseXFixed) - loadedImgData.thumbImgOffsetLeft) * (-loadedImgData.widthDiff) + MidDisplayWidth);
                    // limit image background
//                    if (mouseX != mouseXFixed)
//                    {
//                        bgx = correctPositionByEpsilonBorder(bgx, - loadedImgData.getWidth() + mutableFullWidth, 0, 0);
//                    }

                }

                if (heightCutted)
                {
                    bgy = Math.round((originalFullHeight - loadedImgData.getHeight()) / 2.0);
                }
                else
                {
                    // set Full Image background-position
                    bgy = Math.round((Math.round(mouseYFixed) - loadedImgData.thumbImgOffsetTop) * (-loadedImgData.heightDiff) + MidDisplayHeight);
                    // limit image background
//                    if (mouseY != mouseYFixed)
//                    {
//                        bgy = correctPositionByEpsilonBorder(bgy, - loadedImgData.getHeight() + mutableFullHeight, 0, 0);
//                    }
                }
                
                fullImageContainer.css('backgroundPosition', bgx + 'px' + ' ' + bgy + 'px');
            }
        }

        return this;
    };
})(jQuery);
