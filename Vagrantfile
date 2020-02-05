# -*- mode: ruby -*-
# vi: set ft=ruby :

MACHINES = {
  :proxy1 => {
        :box_name => "centos/7",
        :net => [
                   {ip: '192.168.1.1', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"},
                   {ip: '192.168.10.1', adapter: 3, netmask: "255.255.255.0", virtualbox__generic: "vboxnet1"}
                ]
  },
  :proxy2 => {
        :box_name => "centos/7",
        :net => [
                   {ip: '192.168.1.2', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"},
                   {ip: '192.168.10.2', netmask: '255.255.255.0', adapter: 3, virtualbox__generic: "vboxnet1"}
                ]
  },
  :web1 => {
        :box_name => "centos/7",
        :net => [
                   {ip: '192.168.1.11', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"}
                ]
  },
  :web2 => {
        :box_name => "centos/7",
        :net => [
                   {ip: '192.168.1.12', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"},
                ]
  },
  :db1 => {
        :box_name => "centos/7",
        :net => [
                   {ip: '192.168.1.21', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"}
                ]
  },
  :db2 => {
        :box_name => "centos/7",
        :net => [
                   {ip: '192.168.1.22', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"}
                ]
  },
  :logger => {
        :box_name => "centos/7",
        :net => [
                   {ip: '192.168.1.51', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"}
                ]
  },
  :backup => {
        :box_name => "centos/7",
        :net => [
                   {ip: '192.168.1.52', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"}
                ]
  }
}

Vagrant.configure(2) do |config|
  MACHINES.each do |boxname, boxconfig|
  
    config.vm.define boxname do |box|
      box.vm.box = boxconfig[:box_name]
      box.vm.host_name = boxname.to_s
      boxconfig[:net].each do |ipconf|
        box.vm.network "private_network", ipconf
      end
      config.vm.provider "virtualbox" do |v|
        v.memory = 512
      end
      config.vm.box_check_update = false
      case boxname.to_s
      when "backup"
        box.vm.provider :virtualbox do |vb|
          vb.customize ["storagectl", :id, "--name", "SATA", "--add", "sata" ]
          unless File.exist?("./disk_1.vdi")
            vb.customize ["createhd", "--filename", "./disk_1.vdi", "--variant", "Fixed", "--size", "2048"]
          end
          vb.customize ["storageattach", :id,  "--storagectl", "SATA", "--port", "1", "--device", 0, "--type", "hdd", "--medium", "disk_1.vdi"]
        end
     end
    end
  end
end
