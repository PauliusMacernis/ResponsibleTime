The format of the window list:

  `<window ID> <desktop ID> <client machine> <window title>`
  
  `x` Include WM_CLASS in the window list or interpret <WIN> as the WM_CLASS name.
  `l` List the windows being managed by the window manager. One line  is  output  for  each
                    window,  with  the line broken up into space separated columns.  The first column al‐
                    ways contains the window identity as a hexadecimal integer, and the second column al‐
                    ways  contains  the desktop number (a -1 is used to identify a sticky window). If the
                    -p option is specified the next column will contain the PID for the window as a deci‐
                    mal  integer.  If  the  -G option is specified then four integer columns will follow:
                    x-offset, y-offset, width and height. The next column always contains the client  ma‐
                    chine name. The remainder of the line contains the window title (possibly with multi‐
                    ple spaces in the title).
  `l` Include PIDs in the window list printed by the -l action. Prints a PID of '0' if  the
                    application owning the window does not support it.

The final format after adding the timestamp:

  `<window ID> <desktop ID> <client machine> <window title>`
