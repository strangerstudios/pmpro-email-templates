#---------------------------
# This script generates a new pmproet.pot file for use in translations.
# To generate a new pmproet.pot, cd to the main /pmpro-email-templates/ directory,
# then execute `languages/gettext.sh` from the command line.
# then fix the header info (helps to have the old pmproet.pot open before running script above)
# then execute `cp languages/pmproet.pot languages/pmproet.po` to copy the .pot to .po
# then execute `msgfmt languages/pmproet.po --output-file languages/pmproet.mo` to generate the .mo
#---------------------------
echo "Updating pmproet.pot... "
xgettext -j -o languages/pmproet.pot \
--default-domain=pmproet \
--language=PHP \
--keyword=_ \
--keyword=__ \
--keyword=_e \
--keyword=_ex \
--keyword=_n \
--keyword=_x \
--sort-by-file \
--package-version=1.0 \
--msgid-bugs-address="jason@strangerstudios.com" \
$(find . -name "*.php")
echo "Done!"