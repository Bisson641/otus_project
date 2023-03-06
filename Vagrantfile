# -*- mode: ruby -*-
# vim: set ft=ruby :

MACHINES = {
    :proxy => {
        :box_name => "centos/7",
        :box_version => "1804.02",
        :net => [
                   {ip: '192.168.56.41', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"},
                   {ip: '192.168.58.100', adapter: 3, netmask: "255.255.255.0", virtualbox__generic: "vboxnet1"}
                ]        
    },
    :websrv1 => {
        :box_name => "centos/7",
        :box_version => "1804.02",
        :net => [
                   {ip: '192.168.56.42', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"}
                ]        
    }, 
    :websrv2 => {
        :box_name => "centos/7",
        :box_version => "1804.02",
        :net => [
                   {ip: '192.168.56.43', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"},
                ]        
    },
    :alert => {
        :box_name => "centos/7",
        :box_version => "1804.02",
        :net => [
                   {ip: '192.168.56.44', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"},
                ]        
    },
    :log => {
        :box_name => "centos/7",
        :box_version => "1804.02",
        :net => [
                   {ip: '192.168.56.45', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"}
                ]
    },

    :zabbix => {
        :box_name => "centos/7",
        :net => [
                   {ip: '192.168.56.46', netmask: '255.255.255.0', adapter: 2, virtualbox__intnet: "int-net"},
                   {ip: '192.168.58.101', adapter: 3, netmask: "255.255.255.0", virtualbox__generic: "vboxnet1"}
                ]
    }
}

Vagrant.configure("2") do |config|
    config.vm.box_version = "1804.02"
    MACHINES.each do |boxname, boxconfig|
        config.vm.define boxname do |box|
            box.vm.box = boxconfig[:box_name]
            box.vm.host_name = boxname.to_s
             boxconfig[:net].each do |ipconf|
              box.vm.network "private_network", ipconf
             end
            end#config.vm.define
    end#MACHINES
end #Vagrant.config
