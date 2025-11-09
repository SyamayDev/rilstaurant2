<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// 1. Load necessary models
		$this->load->model('Menu_model');
		$this->load->model('Kategori_model');
		$this->load->model('Settings_model');

		// 2. Fetch all data that contains image assets
		$menus = $this->Menu_model->get_all();
		$categories = $this->Kategori_model->get_all();
		$settings = $this->Settings_model->get_settings();

		$image_urls = [];

		// 3. Compile all image URLs into a single array
		// Add menu images
		foreach ($menus as $menu) {
			if (!empty($menu->gambar)) {
				$image_urls[] = base_url('assets/uploads/' . $menu->gambar);
			}
		}

		// Add category images
		foreach ($categories as $category) {
			if (!empty($category->gambar)) {
				$image_urls[] = base_url('assets/uploads/' . $category->gambar);
			}
		}

		// Add settings images (logo, banners, etc.)
		if ($settings) {
			if (!empty($settings->logo)) $image_urls[] = base_url('assets/img/' . $settings->logo);
			if (!empty($settings->banner1)) $image_urls[] = base_url('assets/img/' . $settings->banner1);
			if (!empty($settings->banner2)) $image_urls[] = base_url('assets/img/' . $settings->banner2);
			if (!empty($settings->banner3)) $image_urls[] = base_url('assets/img/' . $settings->banner3);
			if (!empty($settings->all_categories_icon)) $image_urls[] = base_url('assets/img/' . $settings->all_categories_icon);
		}
		
		// Add static assets
		$image_urls[] = base_url('assets/img/batik.webp');


		// 4. Pass the array of image URLs to the loading view
		$data['images'] = array_unique($image_urls); // Use array_unique to avoid loading the same image multiple times
		$this->load->view('loading_view', $data);
	}
}
