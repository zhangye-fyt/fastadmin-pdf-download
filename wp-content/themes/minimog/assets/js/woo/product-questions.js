(
	function( $ ) {
		'use strict';

		var Helpers = window.minimog.Helpers;

		$( document ).ready( function() {
			questionFormHandler();
			replyHandler();
			getProductQuestions();

			$( document.body ).on( 'minimog_product_question_added', function() {
				var $questionWrap = $( '#minimog-wc-question' );

				if ( $questionWrap.length > 0 ) {
					var $tabPanel = $questionWrap.closest( '.minimog-tabs' );

					if ( $tabPanel.length > 0 ) {
						$tabPanel.MinimogTabPanel( 'updateLayout' );
					}
				}
			} );
		} );

		function getProductQuestions() {
			var $productQuestionWrap = $( '#minimog-wc-question' ),
			    $tabPanel            = $productQuestionWrap.closest( '.minimog-tabs' ),
			    $questionContainer   = $( '.question-list-container', $productQuestionWrap ),
			    $form                = $( '.question-search-form', $productQuestionWrap ),
			    $questionList        = $( '.question-list', $productQuestionWrap ),
			    $questionToolbar     = $( '.question-toolbar', $productQuestionWrap );

			$form.on( 'submit', function( evt ) {
				evt.preventDefault();

				$.ajax( {
					url: $minimog.ajaxurl,
					type: 'GET',
					data: $form.serialize(),
					dataType: 'json',
					cache: true,
					success: function( response ) {
						if ( response.data.fragments ) {
							$.each( response.data.fragments, function( key, value ) {
								$( key ).empty();

								if ( '' !== value ) {
									value = $.trim( value );

									/**
									 * Append only children.
									 * Avoid to use replaceWith to lost "key" events
									 */
									var $newElement = $( $.parseHTML( value ) );
									$( key ).html( $newElement.html() );
								}
							} );

							if ( $tabPanel.length > 0 ) {
								$tabPanel.MinimogTabPanel( 'updateLayout' );
							}

							$( document.body ).trigger( 'minimog_get_product_questions_fragments_loaded' );
						}
					},
					beforeSend: function() {
						Helpers.setBodyHandling();
						var headerHeight = 0,
						    offset       = $questionToolbar.offset().top;

						if ( $( document.body ).hasClass( 'header-sticky-enable' ) ) {
							headerHeight = $( '#page-header' ).outerHeight();
						}

						offset -= headerHeight;
						$( 'html, body' ).animate( { scrollTop: offset }, 800 );
					},
					complete: function() {
						Helpers.setBodyCompleted();
					}
				} );

				return false;
			} );

			$( document.body ).on( 'click', '.question-navigation a', function( evt ) {
				evt.preventDefault();

				var linkParams = Helpers.getUrlParamsAsObject( $( this ).attr( 'href' ) );
				var page = linkParams ? linkParams.current_page : 1;

				$form.find( 'input[name="current_page"]' ).val( page );
				$form.trigger( 'submit' );
			} );
		}

		function questionFormHandler() {
			var $productQuestion   = $( '#minimog-wc-question' ),
			    $noreViews         = $( '.woocommerce-noreviews', $productQuestion ),
			    $questionContainer = $( '.question-list-container', $productQuestion ),
			    $questionList      = $( '.question-list', $productQuestion );

			$( document.body ).on( 'submit', '.question-form', function( evt ) {
				evt.preventDefault();

				var $form            = $( this ),
				    $submitBtn       = $form.find( 'button[type="submit"]' ),
				    $question        = $( '[name=question]', $form ),
				    questionParentID = parseInt( $( 'input[name=question_parent_id]', $form ).val() ),
				    questionCount    = parseInt( $( '.question-count', $questionContainer ).html() );

				$.ajax( {
					url: $minimog.ajaxurl,
					type: 'POST',
					cache: false,
					dataType: 'json',
					data: $form.serialize(),
					success: function( response ) {
						if ( response.success ) {
							if ( questionParentID ) {
								var $questionChildList = $( '.children', '#li-comment-' + questionParentID );
								if ( $questionChildList.length ) {
									$questionChildList.prepend( response.data.response );
								} else {
									$questionChildList = $( '<ol class="children" />' );
									$questionChildList.appendTo( '#li-comment-' + questionParentID ).html( response.data.response );
								}

								$( document.body ).trigger( 'minimog_product_question_child_added', [ questionParentID ] );
							} else {
								// Increasing comment count if it is not children comment.
								questionCount = questionCount + 1;

								// Remove no questions text.
								if ( $noreViews.length ) {
									$noreViews.remove();
								}

								$questionList.prepend( response.data.response );
							}

							var questionText = questionCount === 1 ? response.data.single_text : response.data.plural_text;

							$( '.question-count', $questionContainer ).html( questionCount );
							$( '.question-text', $questionContainer ).html( questionText );

							$question.val( '' );
							$( document.body ).trigger( 'minimog_product_question_added', [ response.data, true ] );

							$form.find( '.question-form-message-box' ).addClass( 'success' ).text( response.data.message ).slideDown();
						} else {
							$form.find( '.question-form-message-box' ).addClass( 'failed' ).text( response.data.message ).slideDown();
						}
					},
					beforeSend: function() {
						$form.find( '.question-form-message-box' ).text( '' ).removeClass( 'failed success' ).slideUp();
						Helpers.setElementHandling( $submitBtn );
					},
					complete: function() {
						Helpers.unsetElementHandling( $submitBtn );
					}
				} );
			} );
		}

		function replyHandler() {
			$( document.body ).on( 'click', '.question-reply', function( evt ) {
				evt.preventDefault();

				var $thisButton         = $( this ),
				    questionID          = $thisButton.data( 'question-id' ),
				    $questionModal      = $( '#modal-product-question' ),
				    $questionModalTitle = $questionModal.find( '.modal-title' ),
				    $formMessages       = $questionModal.find( '.question-form-message-box' ),
				    $questionParent     = $questionModal.find( 'input[name="question_parent_id"]' );

				var questionerName = $thisButton.closest( '.comment-footer' ).siblings( '.meta' ).children( '.fn' ).text();
				var modalTitle = $questionModal.data( 'reply-title' );
				$formMessages.removeClass( 'success failed' ).text( '' );

				modalTitle = modalTitle.replace( '{comment_author_name}', questionerName );
				$questionModalTitle.text( modalTitle );
				$questionModal.MinimogModal( 'open' );
				$questionParent.val( questionID );
			} );
		}
	}
)( jQuery );
