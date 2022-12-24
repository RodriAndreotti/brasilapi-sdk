# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
    config.vm.box = "debian/bullseye64"  
    config.vm.network "forwarded_port", guest: 80, host: 8000, host_ip: "127.0.0.1"
    config.vm.synced_folder "./", "/var/www/html"

    config.ssh.insert_key = false

    config.ssh.private_key_path = "~/.vagrant.d/insecure_private_key"
    config.ssh.extra_args = ["-t", "cd /var/www/html; bash --login"]

    config.vm.provision :shell, path:'vagrant/bootstrap.sh'
    config.vm.provision :shell, path:'vagrant/update.sh', run: 'always'

end
