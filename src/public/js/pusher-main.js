function initPusher($appKey, $appCluster, $logToConsole=false) {
  	// Enable pusher logging - don't include this in production
  	Pusher.logToConsole = $logToConsole;

  	pusher = new Pusher($appKey, {
      	cluster: $appCluster,
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
  		disableStats: true
    });
}
