七， elastic 使用：	https://blog.csdn.net/Zereao/article/details/89341014
	 一, wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-7.6.0-linux-x86_64.tar.gz

	 二, mkdir -p /usr/java 
	 		&& cd /usr/java 
	 		&& cp /root/elasticsearch-7.6.0-linux-x86_64.tar.gz ./ 
	 		&& tar -zxvf elasticsearch-7.6.0-linux-x86_64.tar.gz
	 		&& cd jdk
	 		pwd 
	 		/usr/java/elasticsearch-7.6.0/jdk

	 三, 设置环境变量
	 	vim /etc/profile

	 	export JAVA_HOME=/usr/java/elasticsearch-7.6.0/jdk
	 	export JRE_HOME=/${JAVA_HOME}
	 	export CLASSPATH=.:${JAVA_HOME}/libss:${JRE_HOME}/lib
	 	export PATH=${JAVA_HOME}/bin:$PATH

	 	source /etc/profile
	 	java -version 测试Java是否安装OK

	 四, 使用非root用户进行启动es
	 	创建用户 	useradd es
	 	给用户权限	chown -R es.es elasticsearch-7.6.0
	 	切换到es用户 su es
	 	启动es [es@VM_16_84_centos bin]$ ./elasticsearch
		测试 curl "http://127.0.0.1:9200"
		150.109.46.180:9200

	 五, 修改es配置
	 	[es@VM_16_84_centos config]$ vim elasticsearch.yml
	 	network.host: 0.0.0.0
	 	discovery.seed_hosts: ["127.0.0.1", "[::1]"]

	 六, 配置NGINX
	 	vim /etc/security/limits.conf
* soft nofile 100001
* hard nofile 100002
root soft nofile 100001
root hard nofile 100002
* soft nproc 65535
* hard nproc 65535
* soft nofile 65535
* hard nofile 65535

* soft nofile 65536
* hard nofile 65536
root soft nofile 100001
root hard nofile 100002
* soft nproc 4096
* hard nproc 4096
* soft nofile 65535
* hard nofile 65535
		vim /etc/sysctl.conf
		vm.max_map_count=262144
		sysctl -p
八, 安装 https://github.com/mobz/elasticsearch-head.git
	教程 ：https://blog.csdn.net/qq874455953/article/details/85035364
	https://npm.taobao.org/mirrors/node/v9.9.0/node-v9.9.0-linux-x64.tar.gz

	ln -s /usr/local/node/node-v9.9.0-linux-x64/bin/npm /usr/local/bin/npm
	ln -s /usr/local/node/node-v9.9.0-linux-x64/bin/node /usr/local/bin/node

	http://150.109.46.180:8401/

