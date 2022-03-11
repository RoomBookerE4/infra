# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  # Serveur virtuel de démonstration
  config.vm.define "srv-web" do |machine1|
    machine1.vm.hostname = "srv-web"
    machine1.vm.box = "chavinje/fr-bull-64"
    #machine.vm.box_url = "chavinje/fr-bull-64"
    machine1.vm.network :private_network, ip: "192.168.56.80"
    machine1.vm.network "public_network", 
    	use_dhcp_assigned_default_route: true
    # Un repertoire partagé est un plus mais demande beaucoup plus
    # de travail - a voir à la fin
    #machine.vm.synced_folder "./data", "/vagrant_data", SharedFoldersEnableSymlinksCreate: false

    machine1.vm.provider :virtualbox do |v|
      v.customize ["modifyvm", :id, "--name", "srv-web"]
      v.customize ["modifyvm", :id, "--groups", "/S7-projet"]
      v.customize ["modifyvm", :id, "--cpus", "1"]
      v.customize ["modifyvm", :id, "--memory", 1024]
      v.customize ["modifyvm", :id, "--natdnshostresolver1", "off"]
      v.customize ["modifyvm", :id, "--natdnsproxy1", "off"]
    end
    config.vm.provision "shell", inline: <<-SHELL
      sed -i 's/ChallengeResponseAuthentication no/ChallengeResponseAuthentication yes/g' /etc/ssh/sshd_config    
      sleep 3
      service ssh restart
    SHELL
    machine1.vm.provision "shell", path: "scripts/install_sys.sh"
    machine1.vm.provision "shell", path: "scripts/install_web.sh"
    machine1.vm.provision "shell", path: "scripts/install_roombooker.sh"
    machine1.vm.provision "shell", path: "scripts/install_moodle.sh"
    machine1.vm.provision "shell", path: "scripts/install_myadmin.sh"
  end
  config.vm.define "srv-bdd" do |machine2|
    machine2.vm.hostname = "srv-bdd"
    machine2.vm.box = "chavinje/fr-bull-64"
    #machine.vm.box_url = "chavinje/fr-bull-64"
    machine2.vm.network :private_network, ip: "192.168.56.81"
    machine2.vm.network "public_network", 
    	use_dhcp_assigned_default_route: true
    # Un repertoire partagé est un plus mais demande beaucoup plus
    # de travail - a voir à la fin
    #machine.vm.synced_folder "./data", "/vagrant_data", SharedFoldersEnableSymlinksCreate: false

    machine2.vm.provider :virtualbox do |v2|
      v2.customize ["modifyvm", :id, "--name", "srv-bdd"]
      v2.customize ["modifyvm", :id, "--groups", "/S7-projet"]
      v2.customize ["modifyvm", :id, "--cpus", "1"]
      v2.customize ["modifyvm", :id, "--memory", 1024]
      v2.customize ["modifyvm", :id, "--natdnshostresolver1", "off"]
      v2.customize ["modifyvm", :id, "--natdnsproxy1", "off"]
    end
    config.vm.provision "shell", inline: <<-SHELL
      sed -i 's/ChallengeResponseAuthentication no/ChallengeResponseAuthentication yes/g' /etc/ssh/sshd_config    
      sleep 3
      service ssh restart
    SHELL
    machine2.vm.provision "shell", path: "scripts/install_sys.sh"
    machine2.vm.provision "shell", path: "scripts/install_bdd.sh"
    machine2.vm.provision "shell", inline: "sh /vagrant/scripts/install_backup.sh"
    machine2.vm.provision "shell", path: "scripts/script_cron.sh"
  end

  config.vm.define "srv-proxy" do |machine1|
    machine1.vm.hostname = "srv-proxy"
    machine1.vm.box = "chavinje/fr-bull-64"
    #machine.vm.box_url = "chavinje/fr-bull-64"
    machine1.vm.network :private_network, ip: "192.168.56.90"
    # Un repertoire partagé est un plus mais demande beaucoup plus
    # de travail - a voir à la fin
    #machine.vm.synced_folder "./data", "/vagrant_data", SharedFoldersEnableSymlinksCreate: false

    machine1.vm.provider :virtualbox do |v|
      v.customize ["modifyvm", :id, "--name", "srv-proxy"]
      v.customize ["modifyvm", :id, "--groups", "/S7-projet"]
      v.customize ["modifyvm", :id, "--cpus", "1"]
      v.customize ["modifyvm", :id, "--memory", 1024]
      v.customize ["modifyvm", :id, "--natdnshostresolver1", "off"]
      v.customize ["modifyvm", :id, "--natdnsproxy1", "off"]
    end
    config.vm.provision "shell", inline: <<-SHELL
      sed -i 's/ChallengeResponseAuthentication no/ChallengeResponseAuthentication yes/g' /etc/ssh/sshd_config    
      sleep 3
      service ssh restart
    SHELL
    machine1.vm.provision "shell", path: "scripts/install_sys.sh"
    machine1.vm.provision "shell", path: "scripts/install_web.sh"
    machine1.vm.provision "shell", path: "scripts/reverse_proxy.sh"

  end

end

