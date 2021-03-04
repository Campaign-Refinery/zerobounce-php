<?php declare(strict_types=1);

namespace ZeroBounce\Response;

/**
 * Class SendfileResponse
 * @package ZeroBounce\Response
 */
class SendfileResponse extends Response
{
    /**
     * @return string|null
     */
    public function getFileId(): ?string
    {
        /** @var string $fileId */
        $fileId = $this->getResponseData()['file_id'] ?? null;
        
        return $fileId;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        /** @var string $fileName */
        $fileName = $this->getResponseData()['file_name'] ?? null;
        
        return $fileName;
    }

    /**
     * @return string|null
     */
    public function getUploadDate(): ?string
    {
        /** @var string $uploadDate */
        $uploadDate = $this->getResponseData()['upload_date'] ?? null;
        
        return $uploadDate;
    }

    /**
     * @return string|null
     */
    public function getFileStatus(): ?string
    {
        /** @var string $fileStatus */
        $fileStatus = $this->getResponseData()['file_status'] ?? null;
        
        return $fileStatus;
    }

    /**
     * @return string|null
     */
    public function getCompletePercentage(): ?string
    {
        /** @var string $completePercentage */
        $completePercentage = $this->getResponseData()['complete_percentage'] ?? null;
        
        return $completePercentage;
    }

    /**
     * @return string|null
     */
    public function getReturnUrl(): ?string
    {
        /** @var string $returnUrl */
        $returnUrl = $this->getResponseData()['return_url'] ?? null;
        
        return $returnUrl;
    }
}
