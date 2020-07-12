<?php

$host = "127.0.0.1";
$port = 20205;
// Limits the maximum execution time
set_time_limit(0);

// Creates and returns a socket resource, also referred to as an endpoint of communication. A typical network connection is made up of 2 sockets, one performing the role of the client, and another performing the role of the server.
// socket_create ( int $domain , int $type , int $protocol ) : resource
$sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");

// Bind a name to a socket. Binds the name given in address to the socket described by socket. This has to be done before a connection is be established using socket_connect() or socket_listen().
// socket_bind ( resource $socket , string $address [, int $port = 0 ] ) : bool
/*
address
If the socket is of the AF_INET family, the address is an IP in dotted-quad notation (e.g. 127.0.0.1).

If the socket is of the AF_UNIX family, the address is the path of a Unix-domain socket (e.g. /tmp/my.sock).
*/
$result = socket_bind($sock, $host, $port) or die("Could not bind to socket.\n");

// Listens for a connection on a socket. After the socket socket has been created using socket_create() and bound to a name with socket_bind(), it may be told to listen for incoming connections on socket
$result = socket_listen($sock, 3) or die("Could not set up socket listener\n");
echo "Listening for connections\n";

class Chat{
	function readline(){
		/* rtrim = Remove characters from the right side of a string:
		$str = "Hello World!";   Hello World!
		echo $str . "<br>";		Hello
		echo rtrim($str,"World!"); 
		*/
		return rtrim(fgets(STDIN));
		// fgets returns a string of up to length - 1 bytes read from the file pointed to by handle. If there is no more data to read in the file pointer, then FALSE is returned.
	}
}

do{
	/*
	After the socket socket has been created using socket_create(), bound to a name with socket_bind(), and told to listen for connections with socket_listen(), this function will accept incoming connections on that socket. Once a successful connection is made, a new socket resource is returned, which may be used for communication. If there are multiple connections queued on the socket, the first will be used. If there are no pending connections, socket_accept() will block until a connection becomes present. If socket has been made non-blocking using socket_set_blocking() or socket_set_nonblock(), FALSE will be returned.

	The socket resource returned by socket_accept() may not be used to accept new connections. The original listening socket socket, however, remains open and may be reused.
	socket_accept ( resource $socket ) : resource
	*/
	$accept = socket_accept($sock) or die("Could not accept incoming connection.");

	// The function socket_read() reads from the socket resource socket created by the socket_create() or socket_accept() functions.
	// socket_read ( resource $socket , int $length [, int $type = PHP_BINARY_READ ] ) : string
	$msg = socket_read($accept, 1024) or die("Could not read input \n");

	/*
	The trim() function removes whitespace and other predefined characters from both sides of a string.
	$str = "Hello World!";
	echo $str . "<br>";				Hello World!
	echo trim($str,"Hed!");			llo Worl
	*/
	$msg = trim($msg);
	echo "Client says: \t".$msg."\n\n";

	$line = new Chat();
	echo "Enter Reply: \t";
	$reply = $line->readline();

	//The function socket_write() writes to the socket from the given buffer.
	//socket_write ( resource $socket , string $buffer [, int $length = 0 ] ) : int
	//Returns the number of bytes successfully written to the socket or FALSE on failure. The error code can be retrieved with socket_last_error(). This code may be passed to socket_strerror() to get a textual explanation of the error.
	socket_write($accept, $reply, strlen($reply)) or die("Could not write output\n");
}while(true);

//socket_close() closes the socket resource given by socket. This function is specific to sockets and cannot be used on any other type of resources.
//socket_close ( resource $socket ) : void
socket_close($accept, $sock);


/*
socket_accept() - Accepts a connection on a socket
socket_bind() - Binds a name to a socket
socket_connect() - Initiates a connection on a socket
socket_listen() - Listens for a connection on a socket
socket_last_error() - Returns the last error on the socket
socket_strerror() - Return a string describing a socket error
socket_write() - Write to a socket
*/
?>