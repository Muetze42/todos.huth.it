<?php

namespace App\Http\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GitHubBot
{
    protected PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::withToken(config('services.github.bot-token'))
            ->baseUrl('https://api.github.com');
    }

    public function closeIssueByGitHubIssueUrl(?string $url): ?Response
    {
        if (!$parsed = $this->parseIssueUrl($url)) {
            return null;
        }

        return $this->closeIssue(
            owner: $parsed['owner'],
            repo: $parsed['repo'],
            issueNumber: $parsed['issueNumber']
        );
    }

    public function closeIssue(string $owner, string $repo, int $issueNumber): Response
    {
        $url = sprintf('/repos/%s/%s/issues/%d', $owner, $repo, $issueNumber);

        return $this->client->post($url, ['state' => 'closed']);
    }

    protected function parseIssueUrl(?string $url): ?array
    {
        if (!$url || !Str::isUrl($url)) {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST);

        if ($host != 'github.com') {
            return null;
        }

        $parsed = trim(parse_url($url, PHP_URL_PATH), '/');
        $splits = explode('/', $parsed);

        if (!count($splits) == 4) {
            return null;
        }

        list($owner, $repo, $path, $issueNumber) = $splits;

        if ($path != 'issues') {
            return null;
        }

        return [
            'owner' => $owner,
            'repo' => $repo,
            'issueNumber' => $issueNumber,
        ];
    }

    public function createAnIssueCommentByGitHubIssueUrl(string $message, ?string $url): ?Response
    {
        if (!$parsed = $this->parseIssueUrl($url)) {
            return null;
        }

        return $this->createAnIssueComment(
            message: $message,
            owner: $parsed['owner'],
            repo: $parsed['repo'],
            issueNumber: $parsed['issueNumber']
        );
    }

    public function createAnIssueComment(string $message, string $owner, string $repo, int $issueNumber): Response
    {
        $url = sprintf('/repos/%s/%s/issues/%d/comments', $owner, $repo, $issueNumber);

        return $this->client->post($url, [
            'body' => $message,
        ]);
    }
}
