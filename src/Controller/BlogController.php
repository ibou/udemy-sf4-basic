<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private const POSTS = [
        [
            'id' => 10,
            'title' => 'My first title',
            'slug' => 'my-first-title',
        ],
        [
            'id' => 11,
            'title' => 'My second title',
            'slug' => 'my-second-title',
        ],
        [
            'id' => 12,
            'title' => 'My Third title',
            'slug' => 'my-third-title',
        ],
    ];
    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 2}, requirements={"page"="\d+"})
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get('limit', 15);
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();


        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function ($item) {
                    return $this->generateUrl("blog_by_slug", ["slug" => $item->getSlug()]);
                }, $items)
            ]
        );
    }

    /**
     * Undocumented function
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function post(BlogPost $post = null)
    {
        return $this->json(
            $post
        );
    }

    /**
     * Undocumented function
     * @Route("/post/{slug}", name="blog_by_slug", methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost", options={"mapping": {"slug": "slug"}})
     */
    public function postBySlug(BlogPost $post = null)
    {


        return $this->json(
            $post
        );
    }

    /**
     * Undocumented function
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {

        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();
        return $this->json($blogPost);
    }

    /**
     * Undocumented function
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function delete(BlogPost $post = null)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
