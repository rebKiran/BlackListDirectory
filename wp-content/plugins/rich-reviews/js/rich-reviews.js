jQuery(function() {

	if (typeof moreText === 'undefined' || moreText === '') {
		moreText = 'Read More';
	}
	if (typeof lessText === 'undefined' || lessText === '') {
		lessText = 'Show Less';
	}

	jQuery('.rr_review_text').each(function(){

		if (typeof excerptLength === 'number' && excerptLength > 50 ) {
			max_length = excerptLength;
		} else {
			max_length = 150;
		}

		review_text_element = jQuery(this).find('span[itemprop="reviewBody"]');

		if(review_text_element.text().length > max_length) {
			initExcerpt(review_text_element, moreText, lessText, max_length);
		}
	});

	jQuery('.rr_review_form').each(function() {

		jQuery(this).find('.rr_star').hover(function() {
			renderStarRating(parseInt(jQuery(this).attr('id').charAt(8)), jQuery(this).parent());
		}, function() {
			renderStarRating(parseInt(jQuery(this).parent().closest('form').find('#rRating').val()), jQuery(this).parent());
		});

		jQuery(this).find('.rr_star').click(function(e) {
			thing = jQuery(this).closest('form').find('#rRating');

			thing.val(jQuery(this).attr('id').charAt(8));
		});
	});

	// Above block for multiform
	// jQuery('.rr_star').hover(function() {
	// 	renderStarRating(parseInt(jQuery(this).attr('id').charAt(8)));
	// }, function() {
	// 	renderStarRating(parseInt(jQuery('#rRating').val()));
	// });

	// jQuery('.rr_star').click(function() {
	// 	jQuery('#rRating').val(jQuery(this).attr('id').charAt(8));
	// });
});

function renderStarRating(rating, parent) {
	for (var i=1; i<=5; i++) {
		jQuery(parent).find('#rr_star_'+i).removeClass('glyphicon-star');
		jQuery(parent).find('#rr_star_'+i).removeClass('glyphicon-star-empty');
		if (i<=rating) {
			jQuery(parent).find('#rr_star_'+i).addClass('glyphicon-star');
		} else {
			jQuery(parent).find('#rr_star_'+i).addClass('glyphicon-star-empty');
		}
	}
}

function initExcerpt(text, moreText, lessText, max_length) {

	reviewText = jQuery(text).text();

	reviewLength = max_length;


	if(reviewText.length > reviewLength) {

		if(reviewText.charAt(reviewLength) != ' ') {

			while(reviewText.charAt(reviewLength) != ' ' && reviewText.charAt(reviewLength) != '') {
				reviewLength++;
			}
		}

		if(reviewText.charAt(reviewLength + 1) == '') {
			return; //At the end of the string, no point in creating an excerpt
		}

		var short_content 	= reviewText.substr(0,reviewLength);
		var long_content	= reviewText.substr(reviewLength);

		jQuery(text).html(short_content + '<span class="ellipses">... </span><span class="more_text" style="display:none;">'+ long_content + '</span>');
		jQuery(text).parent().append(function() {
			return '</br><a href="#" class="read_more">' + moreText + '</a><a href="#" class="show_less" style="display:none;">' + lessText + '</a>';
		});
		setTimeout(function() {
			jQuery(text).parent().find('a.read_more').click(function(event){
				event.preventDefault();
				jQuery(event.target).hide();
				jQuery(event.target).parents('.rr_review_text').find('span.ellipses').hide();
				jQuery(event.target).parents('.rr_review_text').find('.more_text').show();
				jQuery(event.target).parents('.rr_review_text').find('a.show_less').show();
				// initExcerpt(text);
			});
			jQuery(text).parent().find('a.show_less').click(function(event){
				event.preventDefault();
				jQuery(event.target).hide();
				jQuery(event.target).parents('.rr_review_text').find('.ellipses').show();
				jQuery(event.target).parents('.rr_review_text').find('.more_text').hide();
				jQuery(event.target).parents('.rr_review_text').find('a.read_more').show();
				// initExcerpt(text);
			});
		}, 300);
	}
}
