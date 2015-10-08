# Hostr (1.1)

[![StyleCI](https://styleci.io/repos/43748520/shield)](https://styleci.io/repos/43748520)

**Hostr is a simple, easy to use and intuitive CLI-based tool that you can use to manage your hosts file.**

I made it because I often work with VMs and I need to configure fake domains for my projects.

## Install Hostr

Installing Hostr is ridiculously simple. All you have to do is to **execute, as `sudo`, this command** from your terminal.

    $ wget http://hellofrancesco.com/projects/hostr.phar && chmod 755 hostr.phar && mv hostr.phar /usr/local/bin/hostr

Once you will have done, verify if everything went well with

    $ hostr

**Note:** always remember to use Hostr as sudo!

## Commands Examples

Here we are: every command you can type in Hostr is here.

    // shows the hosts file records...
    $ hostr show
    
    // adds a record on the hosts file...
    $ hostr add 192.168.10.11 myproject.dev
    
    // adds a record on the hosts file, with aliases...
    $ hostr add 192.168.10.11 myproject.dev --aliases=alias1,alias2
    
    // remove a record, by its ip...
    $ hostr remove ip 192.168.10.11
    
    // .. or by its hostname
    $ hostr remove hostname myproject.dev
    
    // backup the hosts file contents on a "hosts_bk_ file...
    $ hostr backup
    
    // ... so you can restore it, if you do some mistakes. 
    $ hostr restore
    
    // you can always choose the name you want for your backup...
    $ hostr backup --filename="my_backup_file"
    
    // ... and use that file for the restore operation.
    $ hostr restore --filename"my_backup_file"
    
    // if your hosts file is messy and has a lot of double empty lines and tabs, don't worry: just use
    $ hostr tidy
    
    // are you sure that your hosts file is writable?
    $ hostr check

Have fun!

## Change File Paths

The default path for the `hosts` file is `/etc/hosts`.

The default path for the `hosts` file backup is `/etc/hosts_bk`.

However, if you want to change those values for some reasons, feel free to do it by changing values in the `~/.hostr/.env` file. It is automatically created at the first application run.

## Extend Hostr

While creating Hostr, I also wanted to do some practice with interfaces. So, I defined a contract to use if you want to implement a different repository.

You can find this file, `HostsFileRepositoryInterface`, in `app/Contracts`. Once you have defined another repository, you can bind it to the interface without touching the software, using the `app/bindings.php` file.

### The `HostsFileRepositoryInterface`

    <?php
    
    namespace Hostr\Contracts;
    
    
    use Hostr\Core\HostsFile;
    
    interface HostsFileRepositoryInterface
    {
        public function getHostsFile();
        public function saveHostsFile(HostsFile $hostsFile);
    
        public function backup();
        public function restore();
    
        public function tidyUp();
    
        public function isHostsFileWritable();
    }

## Building It

You can build Hostr with [**Box**](https://github.com/box-project/box2).

First of all, clone the repository by typing

    $ git clone http://github.com/francescomalatesta/hostr

and then install dependencies using

    $ composer install --no-dev

Finally, type

    $ box build -v

to start the process.

The `box.json` file is already inside the repository.

## Running Tests

If you want to run Hostr tests, make sure you have **phpunit** installed. Clone this project with

    $ git clone http://github.com/francescomalatesta/hostr

Install dependencies with

    $ composer install

Then, in the root of the project, type

    $ vendor/bin/phpunit

## Credits

Thanks to Emanuele Minotto for the help with Box.
