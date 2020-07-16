/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: ID (Indonesia)
 */
(function ($) {
	$.extend($.validator.messages, {
		required: "Wajib untuk diisi.",
		remote: "Nilai yang dimasukkan sudah ada.",
		email: "Gunakan format email yang valid.",
		url: "Masukkan URL web yang valid.",
		date: "Gunakan format tanggal yang valid.",
		dateISO: "Si us plau, escriu una data (ISO) vàlida.",
		number: "Gunakan format nomor yang valid.",
		digits: "Hanya boleh diisi dengan angka.",
		creditcard: "Si us plau, escriu un número de tarjeta vàlid.",
		equalTo: "Masukkan nilai yang sama.",
		accept: "Masukkan nilai dengan ekstensi yang diterima.",
		maxlength: $.validator.format("Maksimal terdiri dari {0} karakter."),
		minlength: $.validator.format("Minimal terdiri dari {0} karakter."),
		rangelength: $.validator.format("Isian harus terdiri dari {0} s.d {1} karakter."),
		range: $.validator.format("Silahkan masukkan isian antara {0} s.d {1}."),
		max: $.validator.format("Maksimal nilai yang diizinkan adalah {0}."),
		min: $.validator.format("Minimal nilai yang dimasukkan {0}.")
	});
}(jQuery));