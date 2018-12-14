<?php
open my $DEBUG, ">> /path/to/debug/awesomesender.php"
        or die "Can't open the debug file: " $allvalue "!\n"
$sender = new Mail::Sender ({
        ...
        debug => $DEBUG, // sending accept dbg
});