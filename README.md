<!-- Improved compatibility of back to top link: See: https://github.com/othneildrew/Best-README-Template/pull/73 -->
<a id="readme-top"></a>
[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![project_license][license-shield]][license-url]



<!-- PROJECT LOGO -->
<br />
<div align="center">
<h3 align="center">DHL Package API</h3>
  <p align="center">
    This is a Composer package to handle the <a href="https://developer.dhl.com/api-reference/parcel-de-shipping-post-parcel-germany-v2">DHL  Post & Parcel Germany, Parcel API</a> and <a href="https://developer.dhl.com/api-reference/shipment-tracking">the DHL Shipment Tracking - Unified API</a>
    <br />
    <a href="https://github.com/ma-temmel/DHLPackageAPI/"><strong>Explore the docs »</strong></a>
    <br />
    <a href="https://github.com/ma-temmel/DHLPackageAPI//issues/new?labels=bug&template=bug-report---.md">Report Bug</a>
    &middot;
    <a href="https://github.com/ma-temmel/DHLPackageAPI//issues/new?labels=enhancement&template=feature-request---.md">Request Feature</a>
  </p>
</div>



<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#installation">Installation & Usage</a></li>
      </ul>
    </li>
    <li><a href="#license">License</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>


<!-- ABOUT THE PROJECT -->

## About The Project

This is a Composer Package to handle most of the tasks on the DHL API to send packages and track its whereabouts. 
Initially only created for our own needs, but why not release it.
I could not find anything like this. so here you have it... use it like you want.

I used [ mvdnbrk/dhlparcel-php-api ](https://github.com/mvdnbrk/dhlparcel-php-api) as my basis and changed some stuff and build my own version.

This Package uses and older version of "collection" to ensure backwards-compatibility.
Maybe some time in the future I will upgrade it. We will see.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- GETTING STARTED -->
## Getting Started

It's a composer package.. what else to say?

### Installation & Usage

1. Get a API Key at [https://developer.dhl.com](https://developer.dhl.com)
2. Install per composer
   ```sh
   composer require mvdnbrk/dhlparcel-php-api
   ```
3. Enter your API, create a package and send it to DHL
   ```php
    require __DIR__ . '/vendor/autoload.php';
    use Temmel\DHLPackageAPI\Client;
    $dhlparcel = new Client($debug = false);
   
    $dhlparcel->setUserName('USER-NAME');
    $dhlparcel->setUserPassword('USER-PASSWORD');
    $dhlparcel->setClientID('CLIENT-ID');
    $dhlparcel->setClientSecret('CLIENT-SECRET');
   
   	$package_data = [
            'profile' => 'STANDARD_GRUPPENPROFIL',
			'product'       => '',
			'refNo'         => '',
			'billingNumber' => 'YOUR-BILLING-NUMGER',
			'sender'        => [
					'shipperRef' => 'YOUR-OWN-SHIPPERREF-IF-EXISTS',
			],
			'recipient'     => [
					'name1'         => 'NAME',
					'contactName'   => 'CONTACTNAME',
					'addressStreet' => 'STREET (AND NUMBER)',
					'addressHouse'  => 'NUMBER IF NOT IN STREET',
					'number_suffix' => '',
					'postalCode'    => 'POSTALCODE',
					'city'          => 'CITY',
					'country'       => 'COUNTRY (3 LETTERS)',
					'email'         => 'E-MAIL',
			],
             'sender' => [
					'name1' => 'NAME',
					'addressStreet' => 'STREET (AND NUMBER)',
                    'addressHouse'  => 'NUMBER IF NOT IN STREET',
					'additional_address_line' => '',
					'postalCode' => 'POSTALCODE',
					'city' => 'CITY',
					'country' => 'COUNTRY (3 LETTERS)',
					'email' => 'E-MAIL'
            ]
   
	];

	$package = new Package($package_data);
   
   // THIS OPTIONS ARE ALREADY THE STANDARD, CHANGE IF NEEDED
    $package->options->details = [
			'dim'    => [
					'uom'    => 'mm',
					'height' => '100',
					'width'  => '150',
					'length' => '200',
			],
			'weight' => [
					'uom'   => 'g',
					'value' => 500,
			],
	];
   
   $shipment = $dhlparcel->shipments->create($package);
   
   // LOOK UP WHAT YOU NEED ;)
   dump($shipment);
   ```
4. Manifest
   ```php
   $manifests = $dhlparcel->shipments->manifest(['profile' => 'STANDARD_GRUPPENPROFIL', 'billingNumber' => 'YOUR-BILLING-NUMER', 'shipmentNumbers' => [$shipment->shipmentNo]]);
   ```

5. Tracking (This will not work directly after creating the shipping, you must manifest the shipping (or wait till the next day) and then wait another half an hour.)
    ```php
   $tracking = $dhlparcel->tracking->get($shipment->shipmentNo);
   ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- LICENSE -->

## License

Distributed under the project_license. See `LICENSE.MD` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- ACKNOWLEDGMENTS -->

## Acknowledgments

* [ mvdnbrk/dhlparcel-php-api ](https://github.com/mvdnbrk/dhlparcel-php-api)

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->

[contributors-shield]: https://img.shields.io/github/contributors/ma-temmel/DHLPackageAPI.svg?style=for-the-badge

[contributors-url]: https://github.com/ma-temmel/DHLPackageAPI/graphs/contributors

[forks-shield]: https://img.shields.io/github/forks/ma-temmel/DHLPackageAPI.svg?style=for-the-badge

[forks-url]: https://github.com/ma-temmel/DHLPackageAPI/network/members

[stars-shield]: https://img.shields.io/github/stars/ma-temmel/DHLPackageAPI.svg?style=for-the-badge

[stars-url]: https://github.com/ma-temmel/DHLPackageAPI/stargazers

[issues-shield]: https://img.shields.io/github/issues/ma-temmel/DHLPackageAPI.svg?style=for-the-badge

[issues-url]: https://github.com/ma-temmel/DHLPackageAPI/issues

[license-shield]: https://img.shields.io/github/license/ma-temmel/DHLPackageAPI.svg?style=for-the-badge

[license-url]: https://github.com/ma-temmel/DHLPackageAPI/blob/master/LICENSE.MD