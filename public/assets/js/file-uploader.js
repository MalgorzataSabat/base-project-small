
(function($) {
    $.fn.fileUploader = function(options) {

        var options = $.extend({
            //Size of chunks to upload (in bytes)
            'chunkSize': 1000000

            ,scriptPath: 'upload.php'
            ,itemContener: '.itemContener'
            ,dropzoneContener: '#dropzoneContener'
            ,fileInfoField: '.fileInfoField'
            ,autoStart: true

            ,onStart: function(content, i){}
            ,onSendFinished: function(response, i){}
            ,onSavedAllFiles: function(response, i){}

        }, options);


        //var files;

        //Upload specific variables
        var uploadData = [];
        var processUpload = [];


        //Resets all the upload specific data before a new upload
        var resetKey = function(i) {
            uploadData[i] = {
                'uploadStarted': false,
                'file': false,
                'numberOfChunks': 0,
                'aborted': false,
                'paused': false,
                'pauseChunk': 0,
                'key': 0,
                'timeStart': 0,
                'totalTime': 0,
                'start': 0,
                'stop': 0,
                'progress' : 0,
                'content': false
            };
        };


        var sendFile = function(){
            var $this = $(this);
            var $content = $this.closest('[data-file]');
            var $i = $content.attr('data-file');

            if(uploadData[$i].uploadStarted === true &&
                uploadData[$i].paused === false) {
                pauseUpload($i);
            }
            else if(uploadData[$i].uploadStarted === true && uploadData[$i].paused === true) {
                resumeUpload($i);
            }
            else if(processUpload.indexOf($i) < 0){
                addFilesToProcess($i);

                if(processUpload.length === 1){
                    processFiles($i);
                }
            }

            return false;
        };


        //This method cancels the upload of a file.
        //It sets this.uploadData.aborted to true, which stops the recursive upload script.
        //The server then removes the incomplete file from the temp directory, and the html displays an error message.
        var abortFileUpload = function(){
            var $this = $(this);
            var $content = $this.closest('form');
            var $i = $content.attr('data-file');

            if(!uploadData[$i].key || uploadData[$i].aborted){
                uploadData[$i].content.trigger('itemContentFile.sendAborted');
                resetKey($i);
                delete uploadData[$i];
                processUpload.splice(processUpload.indexOf($i),1);

                return false;
            }

            uploadData[$i].content.trigger('itemContentFile.sendAborting');

            uploadData[$i].aborted = true;
            var data = 'key=' + uploadData[$i].key;
            xhr = new XMLHttpRequest();
            xhr.open("POST", options.scriptPath + '?action-file=abort', true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if(xhr.readyState == 4) {
                    var response = JSON.parse(xhr.response);

                    //If there's an error, call the error method.
                    if(response.errorStatus !== 0 || xhr.status != 200) {
                        return;
                    }

                    uploadData[$i].content.trigger('itemContentFile.sendAborted');

                    resetKey($i);

                    delete uploadData[$i];
                    processUpload.splice(processUpload.indexOf($i),1);

                    if(processUpload.length > 0){
                        processFiles(processUpload[0]);
                    }

                }

            };

            //Send the request
            xhr.send(data);

            return false;
        };


        var fileSaved = function(){
            if(processUpload.length == 0){
                options.onSavedAllFiles();
            }
        };


        return this.each(function() {
            var $this = $(this);

            $('body').bind("dragenter dragover", function () {
                $this.addClass("showzone");
                return false;
            });
            $('body').bind("dragleave", function () {
                $this.removeClass("showzone");
                return false;
            });

            if ($this.is("[type='file']")) {
                $this
                    .bind("change", function () {
                        files = this.files;
                        showDropedFiles(files);
                    });
            } else {
                $this
                    //.bind("dragenter dragover", function () {
                    //    $(this).addClass("hover");
                    //    return false;
                    //})
                    //.bind("dragleave", function () {
                    //    $(this).removeClass("hover");
                    //    return false;
                    //})
                    .bind("drop", function (e) {
                        $(this).removeClass("showzone");
                        files = e.originalEvent.dataTransfer.files;
                        showDropedFiles(files);

                        return false;
                    });
            }
        });



        function showDropedFiles(files)
        {
            var itemContener = $(options.itemContener);
            var length = uploadData.length;
            var key = 0;

            for (var i = 0; i < files.length; i++) {
                key = length + i;
                resetKey(key);
                var $content = itemContener.clone();
                uploadData[key].content = $content;
                uploadData[key].file = files[i];

                var rand = Math.random().toString(36).substr(2);
                var filesize = getReadableFileSizeString(uploadData[key].file.size);

                $content.attr('data-file', key);
                $content.attr('data-file-id', rand);
                $content.attr('id',rand);
                //$content.find(options.fileInfoField).html('<span class="filename">'+uploadData[key].file.name+'</span> <span class="icon-grey small">('+filesize+')</span>');


                //$content.attr('data-replace',$content.attr('data-replace')+'-'+rand);
                //$content.find('input.id_form').val(rand);
                //$content.find('input.filesize').val(filesize);
                //

                $(options.dropzoneContener).append($content);
                $content.trigger('itemContentFile.created', [uploadData[key].file]);

                //$content.on('contentFile.fileSaved', fileSaved);
                $content.on('sendFile', sendFile);
                //$content.on('click', '.sendButton', sendFile);
                //$content.on('click', '.stopButton', sendFile);
                //$content.on('click', '.continueButton', sendFile);
                //$content.on('click', '.cancelButton', abortFileUpload);
                //
                if(options.autoStart){
                    $content.trigger('sendFile');
                }

            }

        }


        function addFilesToProcess($i)
        {
            processUpload.push($i);
            uploadData[$i].content.trigger('itemContentFile.pushToSend');

        }

        function processFiles($i){
            //Reset the upload-specific variables
            //resetKey($i);

            //Alias the file input object to this.uploadData
            uploadData[$i].uploadStarted = true;

            uploadData[$i].content.trigger('itemContentFile.startSending');

            //Check the filesize. Obviously this is not very secure, so it has another check in inc/bigUpload.php
            //But this should be good enough to catch any immediate errors
            var fileSize = uploadData[$i].file.size;

            options.onStart(uploadData[$i].content, $i);

            //Calculate the total number of file chunks
            uploadData[$i].numberOfChunks = Math.ceil(fileSize / options.chunkSize);

            //Start the upload
            sendFileData($i, 0);
        }


        //Main upload method
        function sendFileData($i, chunk) {

            //Set the time for the beginning of the upload, used for calculating time remaining
            uploadData[$i].timeStart = new Date().getTime();

            //Check if the upload has been cancelled by the user
            if(uploadData[$i].aborted === true) {
                console.log('return');
                return;
            }

            //Check if the upload has been paused by the user
            if(uploadData[$i].paused === true) {
                uploadData[$i].pauseChunk = chunk;
                uploadData[$i].content.trigger('itemContentFile.sendPaused', [uploadData[$i]]);
                return;
            }


            //Set the byte to start uploading from and the byte to end uploading at
            uploadData[$i].start = chunk * options.chunkSize;
            uploadData[$i].stop = uploadData[$i].start + options.chunkSize;

            //Initialize a new FileReader object
            var reader = new FileReader();

            reader.onloadend = function(evt) {
                xhr = new XMLHttpRequest();

                xhr.open("POST", options.scriptPath + '?action-file=upload&key=' + uploadData[$i].key, true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function() {

                    if(xhr.readyState == 4) {

                        var response = JSON.parse(xhr.response);

                        //If there's an error, call the error method and break the loop
                        if(response.errorStatus !== 0 || xhr.status != 200) {
                            console.log('upload error');
                            return;
                        }

                        //If it's the first chunk, set this.uploadData.key to the server response (see above)
                        if(chunk === 0 || uploadData[$i].key === 0) {
                            uploadData[$i].key = response.key;
                        }

                        //If the file isn't done uploading, update the progress bar and run this.sendFile again for the next chunk
                        if(chunk < uploadData[$i].numberOfChunks) {
                            progressUpdate($i, chunk + 1);
                            sendFileData($i, chunk + 1);
                        }
                        //If the file is complete uploaded, instantiate the finalizing method
                        else {
                            sendFileFinish($i);
                        }
                        //
                    }

                };

                //Send the file chunk
                xhr.send(blob);
            };


            var blob = uploadData[$i].file.slice(uploadData[$i].start, uploadData[$i].stop);
            //reader.readAsBinaryString(blob);
            reader.readAsArrayBuffer(blob);
            //reader.readAsText(blob);//<-- ***Works on both Chrome & IE10 (10.0.9200.16540C0)***
        };



        function progressUpdate($i, progress)
        {
            uploadData[$i].progress = Math.ceil((progress / uploadData[$i].numberOfChunks) * 100);
            uploadData[$i].content.find('.uploadProgress span').css('width', uploadData[$i].progress + '%');
            uploadData[$i].content.find('.uploadProgress span').html(uploadData[$i].progress + '%');

            //Calculate the estimated time remaining
            //Only run this every five chunks, otherwise the time remaining jumps all over the place (see: http://xkcd.com/612/)
            if(uploadData[$i].progress % 5 === 0) {

                //Calculate the total time for all of the chunks uploaded so far
                uploadData[$i].totalTime += (new Date().getTime() - uploadData[$i].timeStart);

                //Estimate the time remaining by finding the average time per chunk upload and
                //multiplying it by the number of chunks remaining, then convert into seconds
                var timeLeft = Math.ceil((uploadData[$i].totalTime / uploadData[$i].progress) * (uploadData[$i].numberOfChunks - uploadData[$i].progress) / 100);

                //Update this.settings.timeRemainingField with the estimated time remaining
                //this.$(this.settings.timeRemainingField).textContent = timeLeft + ' seconds remaining';
            }
        };


        function sendFileFinish($i) {

            if(!uploadData[$i].key){
                return;
            }

            if(uploadData[$i].aborted) {
                return;
            }

            var data = 'key=' + uploadData[$i].key + '&name=' + uploadData[$i].file.name;
            xhr = new XMLHttpRequest();
            xhr.open("POST", options.scriptPath + '?action-file=finish', true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if(xhr.readyState == 4) {
                    var response = JSON.parse(xhr.response);

                    //If there's an error, call the error method
                    if(response.errorStatus !== 0 || xhr.status != 200) {
                        //parent.printResponse(response.errorText, true);
                        return;
                    }

                    //Reset the upload-specific data so we can process another upload

                    uploadData[$i].content.trigger('itemContentFile.sendFinished', [response]);
                    options.onSendFinished(response, uploadData[$i].content, $i);

                    resetKey($i);

                    processUpload.splice(processUpload.indexOf($i),1);
                    if(processUpload.length > 0){
                        processFiles(processUpload[0]);
                    }
                }
            };

            //Send the reques
            xhr.send(data);
        };


        function pauseUpload($i) {
            uploadData[$i].paused = true;
            uploadData[$i].content.trigger('itemContentFile.sendStops', [uploadData[$i]]);
        };


        function resumeUpload($i) {
            uploadData[$i].paused = false;
            uploadData[$i].content.trigger('itemContentFile.sendResume', [uploadData[$i]]);

            sendFileData($i, uploadData[$i].pauseChunk);
        };




        function getReadableFileSizeString(fileSizeInBytes) {

            var i = -1;
            var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
            do {
                fileSizeInBytes = fileSizeInBytes / 1024;
                i++;
            } while (fileSizeInBytes > 1024);

            return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
        };


    };

})(jQuery);
