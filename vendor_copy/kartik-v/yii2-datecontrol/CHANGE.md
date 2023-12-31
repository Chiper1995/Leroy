version 1.9.0
=============
**Date:** 13-Dec-2014

- (bug #34): Locals with short language code like "de" haven't been found because "prefix" was not in string. 
- (bug #34): Bug in Module Methods "getDisplayFormat" and "getSaveFormat" converted a correct php format in an incorrect one.
- (bug #35): Auto convert display and save formats correctly to PHP DateTime format.

version 1.8.0
=============
**Date:** 04-Dec-2014

- (enh #31): Enhance widget to use updated plugin registration from Krajee base 
- (enh #33): Auto validate disability using new `disabled` and `readonly` properties in InputWidget

version 1.7.0
=============
**Date:** 17-Nov-2014

- enh #27: Added property for switching between asynchronous or synchronous request via Ajax.
- enh #28, #29: DateTime createFromFormat wrongly uses current timestamp in time part for bare DATE format.
- Set release to stable.

version 1.6.0
=============
**Date:** 10-Nov-2014

- Set dependency on Krajee base component.

version 1.5.0
=============
**Date:** 10-Oct-2014

1. enh #22: Extension revamped to support PHP and ICU date formats 

version 1.4.0
=============
**Date:** 08-Oct-2014

1. enh #21: Enhance date format conversion based on new yii helper `FormatConverter` (enrica).

version 1.3.0
=============
**Date:** 24-Jul-2014

1. enh #18: Included timezone support for display and save formats (requires `ajaxConversion`).
2. PSR 4 alias change

version 1.2.0
=============
**Date:** 24-Jul-2014

1. (enh #14, #15): Revamped and enhanced datecontrol plugin to work with the [php-date-formatter.js](https://github.com/kartik-v/php-date-formatter) jQuery plugin.
2. The extension now has an option to either use `ajaxConversion` OR use client level javascript validation to convert date. Ajax conversion is disabled by default.
3. Change and Keydown events revamped. The extension now automatically listens to the UP and DOWN presses for the DatePicker widget.
4. Preconfigured locales matching DatePicker. Includes a locales folder for date settings configuration for each language.
5. Ability to override locale date settings at runtime for each DateControl widget instance.

version 1.1.0
=============
**Date:** 26-Jun-2014

1. (bug #3): Fix AutoWidget Plugin Options using right array merge.
2. (enh #4): Fix documentation to include right namespace for Module.
3. (enh #4): Fix documentation to include right namespace for Module.
4. (enh #9): Included `autoWidgetSettings` in module, for configuring global settings for `kartik\widgets` when `autoWidget` is true.
5. (enh #9): Defaulting rules vastly enhanced. Included the configurable properties `dateControlDisplay` and `dateControlSave` in 
   `Yii::$app->params`, which can override the module level `displaySettings` and `saveSettings`.
6. (bug #10): Fix DatePicker convertFormat to work with DateControl.
7. (enh #11): Use date conversion using PHP DateTime instead of Yii formatter
8. (enh #12): Updated documentation for new `autoWidgetSettings` as per enh # 9.

version 1.0.0
=============
**Date:** 01-Jun-2014
Initial release
