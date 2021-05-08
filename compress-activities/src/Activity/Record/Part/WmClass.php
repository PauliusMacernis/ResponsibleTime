<?php
declare(strict_types=1);

namespace ResponsibleTime\Activity\Record\Part;

/**
 * @see https://www.x.org/docs/ICCCM/icccm.pdf
 *
 * WM_CLASS
 * 4.1.2.5. WM_CLASS Property
 * The WM_CLASS property (of type STRING without control characters) contains two consecutive null-terminated strings. These specify the Instance and Class names to be used by both the
 * client and the window manager for looking up resources for the application or as identifying
 * information. This property must be present when the window leaves the Withdrawn state and
 * may be changed only while the window is in the Withdrawn state. Window managers may
 * examine the property only when they start up and when the window leaves the Withdrawn state,
 * but there should be no need for a client to change its state dynamically.
 *
 *
 * The two strings, respectively, are:
 *
 * A string that names the particular instance of the application to which the client that owns
 * this window belongs. Resources that are specified by instance name override any resources
 * that are specified by class name. Instance names can be specified by the user in an operating-system specific manner. On POSIX-conformant systems, the following conventions are
 * used:
 *   − If ‘‘−name NAME’’ is given on the command line, NAME is used as the instance name.
 *   − Otherwise, if the environment variable RESOURCE_NAME is set, its value will be used as the instance name.
 *   − Otherwise, the trailing part of the name used to invoke the program (argv[0] stripped of any directory names) is used as the instance name.
 *
 * A string that names the general class of applications to which the client that owns this window belongs. Resources that are specified by class apply to all applications that have the
 * same class name. Class names are specified by the application writer. Examples of commonly used class names include: ‘‘Emacs’’, ‘‘XTerm’’, ‘‘XClock’’, ‘‘XLoad’’, and so on.
 * Note that WM_CLASS strings are null-terminated and, thus, differ from the general conventions
 * that STRING properties are null-separated. This inconsistency is necessary for backwards compatibility.
 *
 * @see https://askubuntu.com/questions/1060170/xdotool-what-are-class-and-classname-for-a-window
 *
 */
class WmClass extends ActivityRecordPart
{

}
