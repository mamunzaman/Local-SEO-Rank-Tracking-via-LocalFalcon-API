/*
 * Localized default methods for the jQuery validation plugin.
 * Locale: DE
 */
$.extend( $.validator.methods, {
	date: function( value, element ) {
		return this.optional( element ) || /^\d\d?\.\d\d?\.\d\d\d?\d?$/.test( value );
	},
	number: function( value, element ) {
		return this.optional( element ) || /^-?(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d+)?$/.test( value );
	},
	mm_nachname: function( value, element ) {
		return this.optional( element ) || /^-?(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d+)?$/.test( value );
	}
} );
